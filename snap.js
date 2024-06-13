window.snap.pay('SNAP_TRANSACTION_TOKEN', {
    onSuccess: function(result){
      /* You may add your own implementation here */
      alert("payment success!"); console.log(result);
    },
    onPending: function(result){
      /* You may add your own implementation here */
      alert("wating your payment!"); console.log(result);
    },
    onError: function(result){
      /* You may add your own implementation here */
      alert("payment failed!"); console.log(result);
    },
    onClose: function(){
      /* You may add your own implementation here */
      alert('you closed the popup without finishing the payment');
    }
  })