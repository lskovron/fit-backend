<?php

/* Template Name: Results */
get_header();

if(have_posts()): while(have_posts()): the_post();

?>

<div id="overall" style="text-align:center"><h1>Overall FIT Score:<br><span class="total" style="color:#1e7ce1"></span>/<span style="color:#0140c9">20</span></h1></div>

<div id="highcharts-container"></div>

<div id="balance" class="result-section"><h4>FIT Balance Score: <span class="balance"></span></h4>
<?php the_field('balance_score_explanation'); ?>
</div>

<?php $hl = get_field('score_analysis');
if($hl): ?>
<div  class="result-section">
<h4 id="high-title">You scored highest in the <span></span> dimension</h4>
<div id="c-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_cognitive"]; ?></div>
<div id="e-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_emotional"]; ?></div>
<div id="p-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_physical"]; ?></div>
<div id="f-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_financial"]; ?></div>
<div id="s-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_spiritual"]; ?></div>
</div>

<div  class="result-section">
<h4 id="low-title">You scored lowest in the <span></span> dimension</h4>
<div id="c-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_cognitive"]; ?></div>
<div id="e-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_emotional"]; ?></div>
<div id="p-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_physical"]; ?></div>
<div id="f-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_financial"]; ?></div>
<div id="s-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_spiritual"]; ?></div>
</div>

<?php endif; ?>

<div>
<h4>Send results to <span id="email-address"></span></h4>
<button id="send-email">Send email</button>
</div>

<?php

endwhile; endif;

get_footer();

?>