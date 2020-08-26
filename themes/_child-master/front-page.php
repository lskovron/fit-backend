<?php 

get_header();

?>

<div style="text-align:center">
<script>
function buttonClick() {
    // e.preventDefault();
    window.location = 'http://assessment.thefitexperience.com';
}
</script>
    <button id="click-me" onClick="buttonClick()">Take the assessment</button>
</div>

<?php
get_footer();


?>