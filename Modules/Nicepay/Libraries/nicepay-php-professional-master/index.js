$("#va-btn").click(function() {
  $("#va-form").show();
  $("#cc-form").hide();
  $("#va-btn").addClass("active");
  $("#cc-btn").removeClass("active");
});


$("#cc-btn").click(function() {
  $("#va-form").hide();
  $("#cc-form").show();
  $("#va-btn").removeClass("active");
  $("#cc-btn").addClass("active");
});