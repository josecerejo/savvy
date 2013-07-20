INSERT IGNORE INTO schedule SET
  cron="0 3 * * *",
  task="Maintenance",
  enabled=1;
