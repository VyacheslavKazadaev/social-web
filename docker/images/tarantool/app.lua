box.cfg {

    slab_alloc_factor = 2;
    ------------------------
    -- Network configuration
    ------------------------

    -- The read/write data port number or URI
    -- Has no default value, so must be specified if
    -- connections will occur from remote clients
    -- that do not use “admin address”
    listen = 'localhost:3301';
    -- listen = '*:3301';

    -- The server is considered to be a Tarantool replica
    -- it will try to connect to the master
    -- which replication_source specifies with a URI
    -- for example konstantin:secret_password@tarantool.org:3301
    -- by default username is "guest"
    -- replication_source="127.0.0.1:3102";

    -- The server will sleep for io_collect_interval seconds
    -- between iterations of the event loop
    io_collect_interval = nil;

    -- The size of the read-ahead buffer associated with a client connection
    readahead = 16320;

    ----------------------
    -- Memtx configuration
    ----------------------

    -- An absolute path to directory where snapshot (.snap) files are stored.
    -- If not specified, defaults to /var/lib/tarantool/INSTANCE
    -- memtx_dir = nil;

    -- How much memory Memtx engine allocates
    -- to actually store tuples, in bytes.
    memtx_memory = 128 * 1024 * 1024 * 10; -- 128Mb

    -- Size of the smallest allocation unit, in bytes.
    -- It can be tuned up if most of the tuples are not so small
    memtx_min_tuple_size = 16;

    -- Size of the largest allocation unit, in bytes.
    -- It can be tuned up if it is necessary to store large tuples
    memtx_max_tuple_size = 128 * 1024 * 1024 * 10; -- 128Mb

    -- Reduce the throttling effect of box.snapshot() on
    -- INSERT/UPDATE/DELETE performance by setting a limit
    -- on how many megabytes per second it can write to disk
    -- memtx_snap_io_rate_limit = nil;

    ----------------------
    -- Vinyl configuration
    ----------------------

    -- An absolute path to directory where Vinyl files are stored.
    -- If not specified, defaults to /var/lib/tarantool/INSTANCE
    -- vinyl_dir = nil;

    -- How much memory Vinyl engine can use for in-memory level, in bytes.
    vinyl_memory = 128 * 1024 * 1024; -- 128Mb

    -- How much memory Vinyl engine can use for caches, in bytes.
    vinyl_cache = 128 * 1024 * 1024; -- 128Mb

    -- Size of the largest allocation unit, in bytes.
    -- It can be tuned up if it is necessary to store large tuples
    vinyl_max_tuple_size = 128 * 1024 * 1024; -- 128Mb

    -- The maximum number of background workers for compaction.
    vinyl_write_threads = 2;

    ------------------------------
    -- Binary logging and recovery
    ------------------------------

    -- An absolute path to directory where write-ahead log (.xlog) files are
    -- stored. If not specified, defaults to /var/lib/tarantool/INSTANCE
    -- wal_dir = nil;

    -- Specify fiber-WAL-disk synchronization mode as:
    -- "none": write-ahead log is not maintained;
    -- "write": fibers wait for their data to be written to the write-ahead log;
    -- "fsync": fibers wait for their data, fsync follows each write;
    wal_mode = "none";

    -- The maximal size of a single write-ahead log file
    wal_max_size = 256 * 1024 * 1024;

    -- The interval between actions by the checkpoint daemon, in seconds
    checkpoint_interval = 60 * 60; -- one hour

    -- The maximum number of checkpoints that the daemon maintans
    checkpoint_count = 6;

    -- Don't abort recovery if there is an error while reading
    -- files from the disk at server start.
    force_recovery = true;

    ----------
    -- Logging
    ----------

    -- How verbose the logging is. There are six log verbosity classes:
    -- 1 – SYSERROR
    -- 2 – ERROR
    -- 3 – CRITICAL
    -- 4 – WARNING
    -- 5 – INFO
    -- 6 – VERBOSE
    -- 7 – DEBUG
    log_level = 5;

    -- By default, the log is sent to /var/log/tarantool/INSTANCE.log
    -- If logger is specified, the log is sent to the file named in the string
    -- logger = "example.log";

    -- If true, tarantool does not block on the log file descriptor
    -- when it’s not ready for write, and drops the message instead
    log_nonblock = true;

    -- If processing a request takes longer than
    -- the given value (in seconds), warn about it in the log
    too_long_threshold = 0.5;

    -- Inject the given string into server process title
    -- custom_proc_title = 'example';
}

local function bootstrapUser()
    box.schema.user.grant('guest', 'read,write,execute', 'universe')
    local s = box.schema.create_space('UserCache')
    s:format({
    {name = 'id', type = 'unsigned' },
    {name = 'email', type = 'string' },
    {name = 'password', type = 'string' },
    {name = 'auth_key', type = 'string' },
    {name = 'access_token', type = 'string' },
    {name = 'surname', type = 'string' },
    {name = 'first_name', type = 'string' },
    {name = 'age', type = 'unsigned' },
    {name = 'sex', type = 'unsigned' },
    {name = 'interests', type = 'string' },
    {name = 'city', type = 'string' },
     })

    s:create_index('primary', {
     type = 'hash',
     parts = {1, 'unsigned'}
    })
    s:create_index('secondary_surname', {
     type = 'tree',
     unique = false,
     parts = {6, 'string'}
    })
    s:create_index('secondary_first_name', {
     type = 'tree',
     unique = false,
     parts = {7, 'string'}
    })
end

-- for first run create a space and add set up grants
box.once('example-1.0', bootstrapUser)
