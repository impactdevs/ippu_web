<!DOCTYPE html>
<html>
<head>
    <title>HTML to PDF</title>
    <!-- Include the html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <button id="downloadButton">Download as PDF</button>
    <div id="content">
        <!-- Your HTML content to be converted to PDF -->
        <h1>Hello, PDF!</h1>
        <p>This is a sample HTML content.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Get the button and content element
    var downloadButton = document.getElementById('downloadButton');
    var content = document.getElementById('content');

    // Add a click event listener to the button
    downloadButton.addEventListener('click', function() {
        // Create a new html2pdf instance
        var pdfElement = content;
        var pdfOptions = {
            margin: 10,
            filename: 'downloaded.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Generate the PDF
        html2pdf().from(pdfElement).set(pdfOptions).outputPdf();
    });
});

    </script>
</body>
</html>
