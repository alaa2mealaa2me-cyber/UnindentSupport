<?php
header('Content-Type: application/json');

// قم بضبط المسار الصحيح لمجلد PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$response = array('status' => 'error', 'message' => 'حدث خطأ في إرسال الطلب.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من وجود جميع الحقول المطلوبة
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['service']) && isset($_POST['details'])) {
        
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = isset($_POST['phone']) ? $_POST['phone'] : 'غير متوفر';
        $service = $_POST['service'];
        $details = $_POST['details'];

        $mail = new PHPMailer(true);

        try {
            // إعدادات خادم SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // **استبدل هذا بمضيف خادم SMTP الخاص بك**
            $mail->SMTPAuth   = true;
            $mail->Username   = 'alaa.s.awad84@gmail.com'; // **استبدل هذا ببريدك الإلكتروني الذي سيرسل الرسالة**
            $mail->Password   = 'Passw0rd@2574'; // **استبدل هذا بكلمة مرور التطبيق أو كلمة المرور العادية الخاصة بك**
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // ضبط إعدادات اللغة العربية
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            
            // عنوان المرسل والمستلم
            $mail->setFrom('alaa.s.awad84@gmail.com', 'نظام الطلبات التقنية'); // **استبدل هذا ببريدك الإلكتروني**
            $mail->addAddress('UniDents Clinics', 'فريق الدعم'); // **استبدل هذا بالبريد الإلكتروني الذي تريد أن يستقبل الطلبات**
            $mail->addReplyTo($email, $name); // للرد على العميل مباشرة

            // محتوى الرسالة
            $mail->isHTML(true); // لتمكين محتوى HTML في الرسالة
            $mail->Subject = "طلب خدمة تقنية جديد من: $name";
            $mail->Body    = "
                <h3>تفاصيل طلب خدمة تقنية جديد</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr><td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>الاسم الكامل:</td><td style='border: 1px solid #ddd; padding: 8px;'>$name</td></tr>
                    <tr><td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>البريد الإلكتروني:</td><td style='border: 1px solid #ddd; padding: 8px;'>$email</td></tr>
                    <tr><td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>رقم الهاتف:</td><td style='border: 1px solid #ddd; padding: 8px;'>$phone</td></tr>
                    <tr><td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>نوع الخدمة:</td><td style='border: 1px solid #ddd; padding: 8px;'>$service</td></tr>
                    <tr><td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>تفاصيل الطلب:</td><td style='border: 1px solid #ddd; padding: 8px;'>$details</td></tr>
                </table>
            ";
            
            $mail->send();
            $response['status'] = 'success';
            $response['message'] = 'تم إرسال طلبك بنجاح! سيتم التواصل معك قريبًا.';

        } catch (Exception $e) {
            $response['message'] = "فشل إرسال الطلب: {$mail->ErrorInfo}";
        }
    }
}

echo json_encode($response);
?>