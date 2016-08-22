# Swoole 聊天室

### 安装
```php
brew install homebrew/php/php56-swoole
sudo brew services restart httpd24
```

### 使用
运行`swoole websocket server`
```
cd www
cd swoole/socket
php server.php
```
打开`聊天室地址`
```
http://localhost/swoole/socket/client.html
```

### 演示
![演示](http://7xmabi.com1.z0.glb.clouddn.com/blog/swoole-chat-room.gif)
