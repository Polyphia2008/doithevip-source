module.exports = {
  apps: [
    {
      name: 'webapp',
      script: 'php',
      args: '-d upload_max_filesize=20M -d post_max_size=25M -S 0.0.0.0:3000 -t /home/user/webapp /home/user/webapp/dev/router.php',
      cwd: '/home/user/webapp',
      env: { PHP_CLI_SERVER_WORKERS: '4' },
      watch: false,
      instances: 1,
      exec_mode: 'fork'
    }
  ]
}
