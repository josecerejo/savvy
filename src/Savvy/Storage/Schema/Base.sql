INSERT IGNORE INTO schedule SET
  cron="0 3 * * *",
  task="\\Savvy\\Task\\Maintenance",
  active=1;
