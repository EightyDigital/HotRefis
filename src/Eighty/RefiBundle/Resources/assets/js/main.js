// CONTENTS
// 1. Prototypes
// 1.1 - Proto 1
// 1.2 - Proto 2
// 1.3 - Proto 3

// 2. Functions
// 2.1 - Function 1


// Document Ready!
$(function(){
  console.log('Ive loaded');
  //siteBindings.filter__dropdown();
});

var siteBindings = {
  // filter__dropdown: function() {
  //   $('.filter__controller--dropdown > a').mouseover(function(){
  //     $('.filter__controller--dropdown ul').show();
  //   });
  //   $('.filter__controller--dropdown').mouseleave(function(){
  //     $('.filter__controller--dropdown ul').hide();
  //   });
  // }
}

function submitCalc() {
	if( !$('#ltv_at_purchase').val() ) {
    $('.ltv_at_purchase').show();
		return false;
	} else if( !$('#loan_term').val() ) {
    $('.loan_term').show();
		return false;
	} else if( !$('#existing_loan_mortgage_rate').val() ) {
    $('.existing_loan_mortgage_rate').show();
		return false;
	} else {
    $('.error').hide();
		$("#calculator_form").submit();
	}
}

function submitReport() {
	$("#reportform").submit();
}