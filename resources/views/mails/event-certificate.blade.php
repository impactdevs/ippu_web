<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Event Certificate - {{ $event->name }}</title>
</head>
<body style="background-color: #f5f5f5; font-family: sans-serif; padding: 20px;">

    <div style="background-color: #0044cc; padding: 20px; text-align: center; color: white;">
        <img src="{{ url('/logo.png') }}" alt="Event Logo" width="200" height="100">
        <h1 style="margin-top: 10px;">Event Certificate</h1>
    </div>

    <div style="padding: 20px;">
        <p>Dear {{ $name }},</p>
        <p>Congratulations on your participation in the event!</p>
        <p>We are pleased to present you with this certificate for your engagement. Your involvement and enthusiasm are truly valued.</p>
        <p>Below are the details of the event:</p>
        <ul>
            <li><strong>Event Name:</strong> {{ $event->name }}</li>
            <li><strong>Event Dates:</strong> {{ $formattedRange }}</li>
        </ul>
    </div>

    <div style="padding: 20px;">
        <p>We hope this certificate reflects your commitment to professional growth and engagement.</p>
    </div>

    <div style="padding: 20px; text-align: right;">
        <p>Warm regards,</p>
        <p>The Event Management Team</p>
    </div>

    <div style="padding: 20px; background-color: #0044cc; text-align: center; color: white;">
        <p>Stay connected and informed! Download our app today for the latest updates and offers!</p>
        <a href="https://apps.apple.com/th/app/ippu-membership-app/id6467385648" style="color: white">Download our App from the App Store</a><br>
        <a href="https://play.google.com/store/apps/details?id=com.impact.ippu" style="color: white">Download our App from the Play Store</a>
    </div>
</body>
</html>
