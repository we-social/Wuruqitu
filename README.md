# Wuruqitu「误入歧图」

- [ ] 部署到fly.io
- [ ] 修复get_location by ip
- [x] 修复form ajax /up.php
- [x] editorconfig, prettier 格式化
- [x] 支持docker运行 docker-compose
	- [x] 修复mysql charset
	- [x] 修复gd jpeg
	- [x] 临时处理IS_LOCAL
	- [x] 修正ROOT
- [x] 2013年原版commit

__Setup__

```sh
cp conn/dbvars.exmaple.php conn/dbvars.php
# 自行修改 mysql 等变量

cp conn/db_reset.danger.exmaple.php conn/db_reset.danger.php
# 自行修改 custom auth logic 等变量

docker-compose up --build
# 访问 http://localhost/
```
