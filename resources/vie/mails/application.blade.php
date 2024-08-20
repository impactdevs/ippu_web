<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
}
</style>
</head>
<body>

<p>Dear {{ $membership->member->name }},</p>

@if($membership->status == "Pending")
<p>I hope this email finds you well. I am writing to inform you that your application for membership with IPPU has been received and is currently undergoing review. We appreciate your interest and the time you have taken to submit your application.</p>

<p>Our dedicated membership committee is diligently assessing each application to ensure that all requirements and qualifications are met. We understand the importance of building a strong and diverse community, and we are committed to carefully evaluating each applicant's qualifications and fit within our organization.</p>

<p>The review process typically takes [time frame], during which our committee will be thoroughly examining your application, considering your experience, qualifications, and the alignment of your goals with those of our organization. We understand that waiting for a decision can be an anxious time, and we assure you that we are working diligently to provide a timely response.</p>

<p>Rest assured that we hold all applications in strict confidence. Your personal information and the contents of your application will only be shared with the relevant members of our committee who are involved in the evaluation process.</p>

<p>Once the review process is complete, we will notify you of our decision via email. If your application is successful, we will provide you with further details on the next steps, including membership dues, benefits, and any additional requirements.</p>

<p>We appreciate your patience throughout this process and thank you for considering IPPU. We are excited to have the opportunity to review your application and potentially welcome you as a valuable member of our community.</p>

<p>If you have any questions or need further clarification, please feel free to contact our membership department at [contact information]. We will be happy to assist you.</p>

<p>Thank you once again for your interest in joining IPPU. We appreciate your support and enthusiasm.</p>

@elseif($membership->status == "Approved")
Your IPPU membership application has been successfully approved.
You now have access to IPPU’s premium services, promotions and much more. 
Your membership registration is valid from {{ date('jS \of F Y') }} to {{ date('jS \of F Y', strtotime('+1 years')) }}.

@else
Your IPPU membership application has been rejected.
Reason: “{{ $membership->comment }}”

@endif
<p>Best regards,</p>

<p>[Secretary name]<br>
General Secretary<br>
IPPU<br>
[Contact Information]</p>

</body>
</html>
