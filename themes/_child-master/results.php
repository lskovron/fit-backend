<?php

/* Template Name: Results */
get_header();

if(have_posts()): while(have_posts()): the_post();

?>

<div id="overall">Overall FIT Score: <span></span>/20</div>
<div id="balance">FIT Balance Score: <span></span></div>
<?php the_field('balance_score_explanation'); ?>

<?php $hl = get_field('score_analysis');
if($hl): ?>
<h3 id="high-title">You scored highest in the <span></span> dimension</h3>
<div id="c-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_cognitive"]; ?></div>
<div id="e-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_emotional"]; ?></div>
<div id="p-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_physical"]; ?></div>
<div id="f-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_financial"]; ?></div>
<div id="s-high" style="display:none" class="result-analysis"><?php echo $hl["high_score_spiritual"]; ?></div>

<h3 id="low-title">You scored lowest in the <span></span> dimension</h3>
<div id="c-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_cognitive"]; ?></div>
<div id="e-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_emotional"]; ?></div>
<div id="p-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_physical"]; ?></div>
<div id="f-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_financial"]; ?></div>
<div id="s-low" style="display:none" class="result-analysis"><?php echo $hl["low_score_spiritual"]; ?></div>
<?php endif; ?>

<div id="highcharts-container"></div>

<?php

endwhile; endif;

get_footer();

?>