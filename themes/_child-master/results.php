<?php

/* Template Name: Results */
get_header();

if(have_posts()): while(have_posts()): the_post();

?>
<section id="results-page-container">
<h1 class="text-center" id="results-header">Results</h1>
<div id="overall" style="text-align:center"><h2>Overall FIT Score:<br><span class="total" style="color:#1e7ce1"></span>/<span style="color:#0140c9">20</span></h2></div>

<div id="highcharts-container"></div>
<!-- <div style="text-align:center;padding:20px 0 30px;">
    <button id="export-chart">Download my FIT chart</button>
</div> -->

<div id="result-section">
    <h4 style="font-size:18px;font-family:Open Sans,sans-serif;font-weight:400;">Overall FIT Score: <span style="font-weight:700" class="total"></span></h4>
    <h4 style="font-size:18px;font-family:Open Sans,sans-serif;font-weight:400;">FIT Balance Score: <span style="font-weight:700" class="balance"></span></h4>
<?php the_field('score_explanation'); ?>
</div>

<?php $ai = get_field('archetype_images');
if($ai): ?>
<div class="archetype-container">
    <img class="cognitive" style="display:none;" src="<?php echo $ai["cognitive"]; ?>" alt="archetype" />
    <img class="emotional" style="display:none;" src="<?php echo $ai["emotional"]; ?>" alt="archetype" />
    <img class="physical" style="display:none;" src="<?php echo $ai["physical"]; ?>" alt="archetype" />
    <img class="financial" style="display:none;" src="<?php echo $ai["financial"]; ?>" alt="archetype" />
    <img class="spiritual" style="display:none;" src="<?php echo $ai["spiritual"]; ?>" alt="archetype" />
</div>
<?php endif; ?>

<h2 class='dimension-header'>Dimension Scores</h2>

<?php $hl = get_field('subdimension_explanation');
if($hl): 
$cog_subs = ["LEARNING STRATEGIES","INTELLECTUAL ENGAGEMENT","EFFORTFUL CONTROL","ATTENTION","AUTONOMY","SOCIAL COGNITION"];
$emo_subs = ["CURRENT EMOTIONAL HEALTH","SELF-COMPASSION AND EMOTIONAL AWARENESS","STRESS RESILIENCE","GRATITUDE AND POSITIVITY","MINDSET","SOCIAL ENGAGEMENT"];
$fin_subs = ["LONG-TERM PERSPECTIVE","SHORT-TERM PERSPECTIVE","REDUCE SADNESS","INCREASE HAPPINESS","NON-PECUNIARY"];
$phy_subs = ["NUTRITION","NUTRITIONAL KNOWLEDGE","ACTIVITY LEVEL","AEROBIC ACTIVITY","STRENGTH TRAINING","SLEEP HABITS","SELF-IMAGE"];
$spi_subs = ["CONNECTION","COMPASSION AND EMPATHY","FORGIVENESS","PURPOSE","PRESENCE"];
$cog_colors = [ //oranges
    "#ff3a00",
    "#ff4a00",
    "#ff5a00",
    "#ff6a00",
    "#ff7e00",
    "#ff9400",
    "#ffb100",
    "#ffd200"
];
$emo_colors = [ //blues
    "#00296f",
    "#00396f",
    "#004980",
    "#005f92",
    "#007aa9",
    "#009cc1",
    "#08c6de"
];
$phy_colors = [ //reds
    "#a11500",
    "#b51800",
    "#c01a00",
    "#cb1c00",
    "#d72100",
    "#e43304",
    "#f26b3e"
];
$fin_colors = [ //greens
    "#004c00",
    "#005d00",
    "#006e00",
    "#008200",
    "#009906",
    "#1cb423",
    "#68d566"
];
$spi_colors = [ //blue
    "#01188d",
    "#02188d",
    "#071d9c",
    "#1224ac",
    "#2733be",
    "#4d52d1",
    "#8e8ee7"
];
?>
<div  class="result-section">
    <div class="result-analysis">
        <div class="col1" style="border-right:1px solid <?php echo $cog_colors[6]; ?>;">
            <h3 style="color:<?php echo $cog_colors[6]; ?>;">COGNITIVE</h3>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cognitive.svg" />
        </div>
        <div class="col2">
            <h3 class="dim-score"><span id="cognitive-score" style="color:<?php echo $cog_colors[6]; ?>;"></span> out of 20</h3>
            <?php echo $hl["cognitive"]; ?>
            <div class="colored-subdimemsions">
                <?php $i = 0; foreach($cog_subs as $sub){ ?>
                <span style="color:<?php echo $cog_colors[$i];?>"><?php echo $sub; ?></span>
                <?php $i++; } ?>
            </div>
        </div>
    </div>
    <div class="result-analysis">
        <div class="col1" style="border-right:1px solid <?php echo $phy_colors[6]; ?>;">
            <h3 style="color:<?php echo $phy_colors[6]; ?>;">PHYSICAL</h3>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/physical.svg" />
        </div>
        <div class="col2">
            <h3 class="dim-score"><span id="physical-score" style="color:<?php echo $phy_colors[6]; ?>;"></span> out of 20</h3>
            <?php echo $hl["physical"]; ?>
            <div class="colored-subdimemsions">
                <?php $i = 0; foreach($phy_subs as $sub){ ?>
                <span style="color:<?php echo $phy_colors[$i];?>"><?php echo $sub; ?></span>
                <?php $i++; } ?>
            </div>
        </div>
    </div>
    <div class="result-analysis">
        <div class="col1" style="border-right:1px solid <?php echo $fin_colors[6]; ?>;">
            <h3 style="color:<?php echo $fin_colors[6]; ?>;">FINANCIAL</h3>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/financial.svg" />
        </div>
        <div class="col2">
            <h3 class="dim-score"><span id="financial-score" style="color:<?php echo $fin_colors[6]; ?>;"></span> out of 20</h3>
            <?php echo $hl["financial"]; ?>
            <div class="colored-subdimemsions">
                <?php $i = 0; foreach($fin_subs as $sub){ ?>
                <span style="color:<?php echo $fin_colors[$i];?>"><?php echo $sub; ?></span>
                <?php $i++; } ?>
            </div>
        </div>
    </div>
    <div class="result-analysis">
        <div class="col1" style="border-right:1px solid <?php echo $emo_colors[6]; ?>;">
            <h3 style="color:<?php echo $emo_colors[6]; ?>;">EMOTIONAL</h3>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/emotional.svg" />
        </div>
        <div class="col2">
            <h3 class="dim-score"><span id="emotional-score" style="color:<?php echo $emo_colors[6]; ?>;"></span> out of 20</h3>
            <?php echo $hl["emotional"]; ?>
            <div class="colored-subdimemsions">
                <?php $i = 0; foreach($emo_subs as $sub){ ?>
                <span style="color:<?php echo $emo_colors[$i];?>"><?php echo $sub; ?></span>
                <?php $i++; } ?>
            </div>
        </div>
    </div>
    <div class="result-analysis">
        <div class="col1" style="border-right:1px solid <?php echo $spi_colors[6]; ?>;">
            <h3 style="color:<?php echo $spi_colors[6]; ?>;">SPIRITUAL</h3>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/spiritual.svg" />
        </div>
        <div class="col2">
            <h3 class="dim-score"><span id="spiritual-score" style="color:<?php echo $spi_colors[6]; ?>;"></span> out of 20</h3>
            <?php echo $hl["spiritual"]; ?>
            <div class="colored-subdimemsions">
                <?php $i = 0; foreach($spi_subs as $sub){ ?>
                <span style="color:<?php echo $spi_colors[$i];?>"><?php echo $sub; ?></span>
                <?php $i++; } ?>
            </div>
        </div>
    </div>
</div>


<h2 class='marketing-header'>Want the full story?</h2>
<div class="marketing-copy">
    <?php the_field('marketing_copy'); ?>
</div>

<?php endif; ?>

<h4 class="send-results">Send results to <span id="email-address"></span></h4>
<div style="text-align:center;padding:15px 0;">
    <button id="send-email">Email me my results!</button>
    <p id="thank-you-email" style="padding-top:10px;display:none;max-width: 500px;margin: 10px auto;">Thank you for submitting. You'll be receiving an email shortly outlining your scores and what they mean. We are excited to be a part of your journey toward becoming FIT in 5D!</p>
</div>
</div>

<?php

endwhile; endif; ?>
</section>
<?php
get_footer();

?>