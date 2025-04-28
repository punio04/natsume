<?php
// 文字コード設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// フォームから受け取るデータ
$name = htmlspecialchars($_POST['name01'], ENT_QUOTES, 'UTF-8');
$tel = htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$qualification = htmlspecialchars($_POST['qualification'], ENT_QUOTES, 'UTF-8');
$experience = htmlspecialchars($_POST['experience'], ENT_QUOTES, 'UTF-8');
$pr = htmlspecialchars($_POST['pr'], ENT_QUOTES, 'UTF-8');

// メール内容
$to = "gude_0417@icloud.com"; // ここに送信先アドレスを書く！
$subject = "お問い合わせフォームからの送信";
$body = "
氏名: {$name}\n
携帯番号: {$tel}\n
メールアドレス: {$email}\n
応募職種: {$qualification}\n
経験年数: {$experience}\n
備考欄:\n
{$pr}
";

$headers = "From: {$email}\n";

// メール送信
if (mb_send_mail($to, $subject, $body, $headers)) {
  header("Location: thanks.html"); // 送信成功したらサンクスページへ
  exit;
} else {
  echo "送信に失敗しました。";
}
