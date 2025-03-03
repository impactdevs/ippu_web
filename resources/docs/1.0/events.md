# Events

---

- [Events](#events)

<a name="events"></a>
## Booking an Event Attendance.  
To book an event Attendance, follow the following steps. 

1. In the side-bar, click Events(**Events>All Events**) as highlighted in Red.  

<img src="{{ asset('/assets/docs/events-side-bar.png') }}" alt="Events Side Bar">  

2. You will be redirected to a page with all the events in the system as shown below.  

<img src="{{ asset('/assets/docs/events-list.png') }}" alt="Events List">  

3. Click on the event and you will be redirected to event details which looks like below.  

<img src="{{ asset('/assets/docs/event-details.png') }}" alt="Event Details">  

4. On the right bottom of the event banner is the following:  

### Event Statuses:

**I. Upcoming Event**:
   - If the event's start date is in the future, it means the event is yet to happen.
   - You will see an option to **Book Attendance** if you haven’t already booked.

**II. Ongoing Event**:
   - If the event is currently happening (i.e., the start date is today or earlier, and the end date is in the future), it means the event is in progress.
   - If you haven’t booked yet, you’ll see an option to **Book Attendance**.
   - If you’ve already booked, you will see a message confirming your booking. If you still have a balance to pay, you’ll see an option to **Complete Payment**.

**III. Event Ended**:
   - If the event's end date has passed, the event is over.
   - If you attended the event, you will be able to download any available resources and view your **Certificate**.
   - If you didn’t attend, you’ll see a message that the event has ended.

**IV. Booking Status: Confirmed or Pending**:
   - If you've booked to attend an event but haven't completed payment yet, your status will show as **Confirmed** or **Pending**.
   - If your balance is zero, you’ll see a message confirming your booking. If you still owe money, you'll see an option to **Complete Payment**.

**V. Attended Event**:
   - If you successfully attended the event, you’ll be able to download any resources the event organizer has made available and view your **Certificate** for attending. 

5. From the previous screenshot, we can start the booking process by clicking "Book" button on the bottom left of the event banner.You will be presented with a modal that has a form where you select the payment mode(Cash or Cashless) and the amount field with the event fee as the default(you can edit this amount to what you have for booking). the following steps are for cashless booking.    

<img src="{{ asset('/assets/docs/booking-modal.png') }}" alt="Booking Modal">  

6. Click "Proceed With Booking" button and you will be redirected to a flutterwave checkout page as shown below.  

<img src="{{ asset('/assets/docs/flw-checkout.png') }}" alt="FLW CHECKOUT">  

7. Follow the flutterwave instructions and after the payment is successful, you will be redirected to IPPU with a success message.  

> {success} You have successfully booked an event. but remember we paid full amount and you should see the message **"You booked to attend this event"** as shown in the screenshot below.  

<img src="{{ asset('/assets/docs/booked-attendance.png') }}" alt="Booked attendance">  

>{success} when you pay part of the booking fee(e.g 20,000), you will still have an option of completing the payment by clicking "complete balance(amount)" button as shown in the screenshot below. The steps are the same as when making initial payment.  

<img src="{{ asset('/assets/docs/partial-booking.png') }}" alt="Partial Booking">  

>{danger}**For Cash option, you wont be asked to enter any amount in the booking modal. Just click proceed with booking and that's it, you request will be received by the IPPU admin.**  

<img src="{{ asset('/assets/docs/cash-booking.png') }}" alt="Partial Booking">  

**That's all you need to know about events booking**

## Generating your event attendance Certificate.  

So, once the event day has reached, IPPU will provide a link or QR code to scan at the end of the event. The form will have fields that look like the form below.  

<img src="{{ asset('/assets/docs/attendance.png') }}" alt="Partial Booking"> 

Complete the form fields and submit.  

>{danger} Once you submit your attendance, the admin will approve it and the next time you login to IPPU and check the event, it should have a "Certificate" button as shown below.  

<img src="{{ asset('/assets/docs/download-certificate.png') }}" alt="Certificate Download"> 

**You will be prompted to save the certificate and whn you open it, it will look like one below**  

<img src="{{ asset('/assets/docs/certificate-generated.png') }}" alt="Certificate Download"> 

>{warning}The certificate does not necesarily look like one above since IPPU might keep requesting for a change in their certificate template.  













