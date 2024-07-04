function initiateSnapPayment(snapToken) {
  window.snap.pay(snapToken, {
      onSuccess: function(result){
          alert("Payment successful!");
          console.log(result);
          window.location.href = './my-cart.php'; // Redirect to your desired page after successful payment
      },
      onPending: function(result){
          alert("Waiting for your payment!");
          console.log(result);
          window.location.href = './my-cart.php'; // Redirect to your desired page while waiting for payment
      },
      onError: function(result){
          alert("Payment failed!");
          console.log(result);
          window.location.href = './my-cart.php'; // Redirect to your desired page after payment failure
      },
      onClose: function(){
          alert('You closed the popup without finishing the payment.');
      }
  });
}
