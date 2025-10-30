// Minimal JS: no frameworks. Mostly progressive enhancement.
// (Cart is handled by server forms; JS can be used for UI niceties.)
document.addEventListener('DOMContentLoaded', function(){
  // Example: confirm before clearing cart link
  document.querySelectorAll('a[href*="action=clear"]').forEach(a=>{
    a.addEventListener('click', function(e){
      if(!confirm('Clear cart?')) e.preventDefault();
    });
  });
});
