
<?php
// 设置机器人的token
$botToken = "123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ";
// 设置telegram api的网址
$website = "https://api.telegram.org/bot".$botToken;

// 从php输入流中获取更新信息，并将其解码为数组
$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

// 获取聊天id
$chatId = $update["message"]["chat"]["id"];
// 获取消息文本，并进行防注入处理
$message = htmlspecialchars($update["message"]["text"]);
// 获取消息发送时间戳
$timestamp = $update["message"]["date"];

// 根据消息文本进行不同的回复
switch($message) {
    case "/starr":
        sendMessage($chatId, "欢迎你 yours，\n你可以使用此bot向 @myadminid 发送讯息");
        break;
    case "hello":
        sendMessage($chatId, "2");
        break;
    default: 
        sendMessage($chatId, "我不明白你的意思");
}
 
// 向指定的聊天id发送消息函数 
function sendMessage ($chatId, $message) {

    // 拼接发送消息的url，并将消息文本进行url编码 
    $url = $GLOBALS[website]."/sendMessage?chat_id=".$chatId."&text=".urlencode($message);

    // 发送请求 
    file_get_contents($url);

    // 将用户id、用户发送的消息、时间戳以及bot回复的消息写入日志文件中 
    $log = fopen("日志.csv", "a") or die("Unable to open file!"); 

    // 防止SQL注入，使用addslashes()函数对特殊字符进行转义处理 
    fwrite($log, $chatId.":".addslashes($message)." bot:".addslashes($message)." ".$timestamp."\n"); 

    fclose($log); 

    // 防止XSS注入，使用htmlspecialchars()函数对特殊字符进行转义处理 
    echo htmlspecialchars($message); 
}