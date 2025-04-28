<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// env読み込み
$config = require __DIR__ . '/env.php';

// セッション開始
session_start();

// POSTデータ取得
$form_data = $_POST;

// 🔹送信先メールアドレス
$recipients = [
  'gude_0417@icloud.com',
  'akari_0417@i.softbank.jp'
];

$mail = new PHPMailer(true);

try {
  // SMTP設定
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = $config['GMAIL_USERNAME'];
  $mail->Password = $config['GMAIL_APP_PASSWORD'];
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  // 送信者
  $mail->setFrom($form_data['email'], "=?UTF-8?B?" . base64_encode("なつめ整体院") . "?=");

  // 受信者
  foreach ($recipients as $recipient) {
    $mail->addAddress($recipient);
  }

  // 件名
  $mail->Subject = "=?UTF-8?B?" . base64_encode("【なつめ整体院】応募がありました") . "?=";

  // 本文
  $mail->isHTML(true);
  $mail->CharSet = "UTF-8";
  $mail->Encoding = "base64";
  $mail->Body = "
    <p><strong>氏名:</strong> {$form_data['name01']}</p>
    <p><strong>携帯番号:</strong> {$form_data['tel']}</p>
    <p><strong>メールアドレス:</strong> {$form_data['email']}</p>
    <p><strong>応募職種:</strong> {$form_data['qualification']}</p>
    <p><strong>経験年数:</strong> {$form_data['experience']}</p>
    <p><strong>備考欄:</strong><br> " . nl2br(htmlspecialchars($form_data['pr'] ?? "なし", ENT_QUOTES)) . "</p>
  ";

  // 送信！
  $mail->send();
} catch (Exception $e) {
  echo "<script>alert('管理者への送信に失敗しました: {$mail->ErrorInfo}'); history.back();</script>";
  exit;
}

// 🔹ユーザーへの自動返信
$user_mail = new PHPMailer(true);

try {
  $user_mail->isSMTP();
  $user_mail->Host = 'smtp.gmail.com';
  $user_mail->SMTPAuth = true;
  $user_mail->Username = $config['GMAIL_USERNAME'];
  $user_mail->Password = $config['GMAIL_APP_PASSWORD'];
  $user_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $user_mail->Port = 587;

  $user_mail->setFrom($config['GMAIL_USERNAME'], "なつめ整体院");
  $user_mail->addAddress($form_data['email']);

  $user_mail->Subject = "=?UTF-8?B?" . base64_encode("【なつめ整体院】ご応募ありがとうございます") . "?=";
  $user_mail->isHTML(true);
  $user_mail->CharSet = "UTF-8";
  $user_mail->Encoding = "base64";
  $user_mail->Body = "
    <p>{$form_data['name01']} 様</p><br>
    <p>この度はご応募いただき、誠にありがとうございます。</p>
    <p>以下の内容で受付いたしましたのでご確認ください。</p><br>
    <hr><br>
    <p><strong>■ 氏名:</strong> {$form_data['name01']}</p>
    <p><strong>■ 携帯番号:</strong> {$form_data['tel']}</p>
    <p><strong>■ メールアドレス:</strong> {$form_data['email']}</p>
    <p><strong>■ 応募職種:</strong> {$form_data['qualification']}</p>
    <p><strong>■ 経験年数:</strong> {$form_data['experience']}</p>
    <p><strong>■ 備考欄:</strong><br> " . nl2br(htmlspecialchars($form_data['pr'] ?? "なし", ENT_QUOTES)) . "</p>
    <br><hr><br>
    <p>後日担当よりご連絡差し上げますので、今しばらくお待ちください。</p>
    <br>
    <p>なつめ整体院</p>
  ";

  $user_mail->send();
} catch (Exception $e) {
  echo "<script>alert('自動返信に失敗しました: {$user_mail->ErrorInfo}'); history.back();</script>";
  exit;
}

// 完了メッセージ＋リダイレクト
echo "<script>alert('送信完了しました。ご応募ありがとうございます！'); window.location.href='thanks.html';</script>";
exit;
