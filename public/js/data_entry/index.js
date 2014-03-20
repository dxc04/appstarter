var app = angular.module('BvApp',[]);

app.directive('numbersOnly', function() {
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {
       modelCtrl.$parsers.push(function (inputValue) {
           if (inputValue == undefined) return '' 
           var transformedInput = inputValue.replace(/[^\.0-9]/g, ''); 
           if (transformedInput!=inputValue) {
              modelCtrl.$setViewValue(transformedInput);
              modelCtrl.$render();
           }         

           return transformedInput;         
       });
     }
   };
});

$(document).ready(function () {
	$("#remuneration_number_of_director_shareholders").change(function() {
		enableDirectors($(this).val());
	});
	
	enableDirectors($("#remuneration_number_of_director_shareholders").val());
});

function enableDirectors(i) {
	var val = $("#remuneration_directors_salary").val();
	
	for (j = 0; j < i; j++) {
		$("#directors_percentage_of_shares_" + (j + 1)).removeAttr('disabled');
		$("#directors_salary_paid_" + (j + 1)).removeAttr('disabled');
		$("#directors_salary_paid_" + (j + 1)).val(val);
		$("#directors_other_taxable_income_" + (j + 1)).removeAttr('disabled');
		$("#directors_balance_on_directors_loan_account_" + (j + 1)).removeAttr('disabled');
	}
	
	for (i = j; i <= 4; i++) {
		$("#directors_percentage_of_shares_" + (i + 1)).attr('disabled', 'disabled');
		$("#directors_salary_paid_" + (i + 1)).attr('disabled', 'disabled');
		$("#directors_salary_paid_" + (i + 1)).val('');
		$("#directors_other_taxable_income_" + (i + 1)).attr('disabled', 'disabled');
		$("#directors_balance_on_directors_loan_account_" + (i + 1)).attr('disabled', 'disabled');
	}
}
