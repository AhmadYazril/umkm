DB::statement("INSERT IGNORE INTO migrations (migration, batch) VALUES ('0001_01_01_000000_create_users_table', 0), ('0001_01_01_000001_create_cache_table', 0), ('0001_01_01_000002_create_jobs_table', 0)");
echo "Done";
