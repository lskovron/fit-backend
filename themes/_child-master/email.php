<?php

function return_email_template(
  $data
  // $highestDim,
  // $lowestDim,
  // $cognitive,
  // $emotional,
  // $physical,
  // $financial,
  // $spiritual,
  // $overall,
  // $email,
  // $participant,
  // $balance,
  // $urlString
){
  extract($data);

$emailPage = get_page_by_path( 'email-copy' );
$emailPageId = $emailPage->ID;

$resultsPage = get_page_by_path( 'results' );
$resultsPageId = $resultsPage->ID;

$marketingText = get_field('marketing_copy',$emailPageId);
$marketingImages = '';
$archUrl = '';
$archText = '';
$cogText = '';
$phyText = '';
$finText = '';
$emoText = '';
$spiText = '';

$archCode = substr($highestDim, 0, 1).substr($lowestDim, 0, 1);

if( have_rows('marketing_copy_images',$emailPageId) ):   while ( have_rows('marketing_copy_images',$emailPageId) ) : the_row();
$marketingImages .= '<img src="'.get_sub_field('image').'" />';
endwhile; endif;

if(get_field('archetype_images',$resultsPage)){
  $archUrl = get_field('archetype_images',$resultsPage)[$highestDim];
}

if(get_field('archetype_text',$emailPageId)){
  $archText = get_field('archetype_text',$emailPageId)[$archCode];
}

if( (float)$cognitive < 5 ){
  $cogText = get_field('dimension_scores',$emailPageId)['cognitive_5'];
} elseif( (float)$cognitive < 10 ) {
  $cogText = get_field('dimension_scores',$emailPageId)['cognitive_5_10'];
} elseif( (float)$cognitive < 15 ) {
  $cogText = get_field('dimension_scores',$emailPageId)['cognitive_10_15'];
} else {
  $cogText = get_field('dimension_scores',$emailPageId)['cognitive_15_20'];
}

if( (float)$physical < 5 ){
  $phyText = get_field('dimension_scores',$emailPageId)['physical_5'];
} elseif( (float)$physical < 10 ) {
  $phyText = get_field('dimension_scores',$emailPageId)['physical_5_10'];
} elseif( (float)$physical < 15 ) {
  $phyText = get_field('dimension_scores',$emailPageId)['physical_10_15'];
} else {
  $phyText = get_field('dimension_scores',$emailPageId)['physical_15_20'];
}

if( (float)$financial < 5 ){
  $finText = get_field('dimension_scores',$emailPageId)['financial_5'];
} elseif( (float)$financial < 10 ) {
  $finText = get_field('dimension_scores',$emailPageId)['financial_5_10'];
} elseif( (float)$financial < 15 ) {
  $finText = get_field('dimension_scores',$emailPageId)['financial_10_15'];
} else {
  $finText = get_field('dimension_scores',$emailPageId)['financial_15_20'];
}

if( (float)$emotional < 5 ){
  $emoText = get_field('dimension_scores',$emailPageId)['emotional_5'];
} elseif( (float)$emotional < 10 ) {
  $emoText = get_field('dimension_scores',$emailPageId)['emotional_5_10'];
} elseif( (float)$emotional < 15 ) {
  $emoText = get_field('dimension_scores',$emailPageId)['emotional_10_15'];
} else {
  $emoText = get_field('dimension_scores',$emailPageId)['emotional_15_20'];
}

if( (float)$spiritual < 5 ){
  $spiText = get_field('dimension_scores',$emailPageId)['spiritual_5'];
} elseif( (float)$spiritual < 10 ) {
  $spiText = get_field('dimension_scores',$emailPageId)['spiritual_5_10'];
} elseif( (float)$spiritual < 15 ) {
  $spiText = get_field('dimension_scores',$emailPageId)['spiritual_10_15'];
} else {
  $spiText = get_field('dimension_scores',$emailPageId)['spiritual_15_20'];
}




$email_html = '';
// ob_start();

$email_html .= '<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@700&display=swap" rel="stylesheet">
    <title>Your FIT Assessment Results</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      
      /*All the styling goes here*/
      
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; 
      }

      body {
        background-color: #f6f6f6;
        font-family: Open Sans,sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; 
      }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: Open Sans,sans-serif;
          font-size: 14px;
          vertical-align: top; 
      }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; 
      }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 860px;
        padding: 10px;
        width: 860px; 
      }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 860;
        padding: 10px; 
      }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; 
      }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; 
      }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%; 
      }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; 
      }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #576a7c;
        font-family: Open Sans, sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 15px; 
      }

    h1 {
        font-size: 35px;
        font-weight: 700;
    }

    h2 {
        font-size: 28px;
        font-weight: 700;
    }

    h3 {
        font-size: 24px;
        font-weight: 700;
    }

    h4 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 0;
    }

      p,
      ul,
      ol {
        font-family: Open Sans, sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px; 
      }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; 
      }

      a {
        color: #3498db;
        text-decoration: underline; 
      }
      p{
        font-size: 16px;
        line-height: 1.4;
        }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; 
      }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; 
      }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; 
      }

      .btn-primary table td {
        background-color: #3498db; 
      }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; 
      }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; 
      }

      .first {
        margin-top: 0; 
      }

      .align-center {
        text-align: center; 
      }

      .align-right {
        text-align: right; 
      }

      .align-left {
        text-align: left; 
      }

      .clear {
        clear: both; 
      }

      .mt0 {
        margin-top: 0; 
      }

      .mb0 {
        margin-bottom: 0; 
      }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; 
      }

      .powered-by a {
        text-decoration: none; 
      }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        margin: 20px 0; 
      }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; 
        }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; 
        }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; 
        }
        table[class=body] .content {
          padding: 0 !important; 
        }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; 
        }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; 
        }
        table[class=body] .btn table {
          width: 100% !important; 
        }
        table[class=body] .btn a {
          width: 100% !important; 
        }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; 
        }
      }

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; 
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; 
        }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; 
        }
        #MessageViewBody a {
          color: inherit;
          text-decoration: none;
          font-size: inherit;
          font-family: inherit;
          font-weight: inherit;
          line-height: inherit;
        }
        .btn-primary table td:hover {
          background-color: #34495e !important; 
        }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; 
        } 
      }


      .serif {
          font-family: Libre Baskerville, serif;
          font-weight: 700;
          color: #576a7c;

      }

      h2.main-header {
          font-size: 1.5em;
          padding: 10px;
          border: 1px solid #576a7c;
      }
      .imgRow {
          display: inline-block;
          padding: 0 5px;
      }
      .imgRow img {
          width: 55px;
      }
      .imgRow span {
          font-family: Open Sans Condensed;
          display: block;
          font-size: 11px;
            text-align: center;
      }
      .colored-subdimemsions span {
          font-family: Open Sans Condensed;
          font-size: 18px;
      }
      .colored-subdimemsions p {
          margin-bottom: 5px;
      }

      .dim-copy p {
        color:#a0a9b3;
        font-size: 16px;
        line-height: 1.4;
      }

      .dimension-header h2 {
        font-size: 1.5rem;
        text-align: center;
        font-weight: 400;
        position: relative;
        margin-bottom: 20px;
        font-family: Libre Baskerville;
      }
      .dimension-header div {
        width: 60px;
        border-bottom: 1px solid;
        margin: 0 auto;
      }

      .marketing-text h1,
      .marketing-text h2,
      .marketing-text h3,
      .marketing-text h4 {
          font-family: Libre Baskerville, serif;
      }


    </style>
  </head>
  <body class="">
    <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                  <td class="wrapper">
                      <table>
                          <tr>
                              <td style="text-align:center;">
                                <img width="300" src="';
$email_html .= get_stylesheet_directory_uri();
$email_html .= 
                                '/images/logo.png" alt="fit logo" id="logo">
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td class="wrapper">
                      <table>
                          <tr>
                              <td style="text-align:center;">
                                <h2 class="serif main-header">DETAILED RESULTS</h2>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td class="wrapper">
                      <table>
                          <tr>
                              <td style="text-align:center;">
                                <h2 class="serif overall">
                                    Overall FIT Score
                                    <br>
                                    <span class="total" style="color:#1e7ce1">';
$email_html .= $overall;
$email_html .= '</span>
                                    <span style="font-weight:300"> / </span>
                                    <span style="color:#0140c9">20</span>
                                </h2>
                                <img width="300" src="';
$email_html .= get_site_url();
$email_html .= '/wp-content/uploads/2020/09/Screen-Shot-2020-09-16-at-3.39.33-PM.png" />
                                <br />
                                <p style="font-weight: 700;color: #576a7c;margin-top: 10px;">View your CircumFIT chart
                                    <a href="http://results.thefitexperience.com/results/';
$email_html .= $urlString;
$email_html .= '">here</a>
                                </p>
                            </td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td class="wrapper">
                      <table>
                          <tr>
                              <td style="text-align:center;color:#a0a9b3;">
                                <img width="400" src="';
$email_html .= $archUrl;
$email_html .= '" alt="archetype">';
$email_html .= $archText;
$email_html .=
                            '</td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td class="wrapper">
                      <table style="border: 1px solid #a0a9b3;padding: 30px 0;">
                          <tr>
                            <td style="width:50%;text-align:right;">
                                <div class="imgRow">
                                    <span style="color: rgb(255, 177, 0);">COGNITIVE</span>
                                    <img src="'.get_site_url().'/wp-content/uploads/2020/09/cog.png" alt="dimension icon">
                                </div>
                                <div class="imgRow">
                                    <span style="color: rgb(228, 51, 4);">PHYSICAL</span>
                                    <img src="'.get_site_url().'/wp-content/uploads/2020/09/phy.png" alt="dimension icon">
                                </div>
                                <div class="imgRow">
                                    <span style="color: rgb(104, 213, 102);">FINANCIAL</span>
                                    <img src="'.get_site_url().'/wp-content/uploads/2020/09/fin.png" alt="dimension icon">
                                </div>
                                <div class="imgRow">
                                  <span style="color: rgb(8, 198, 222);">EMOTIONAL</span>
                                  <img src="'.get_site_url().'/wp-content/uploads/2020/09/emo.png" alt="dimension icon">
                                </div>
                                <div class="imgRow">
                                    <span style="color: rgb(142, 142, 231);">SPIRITUALITY</span>
                                    <img src="'.get_site_url().'/wp-content/uploads/2020/09/spi.png" alt="dimension icon">
                                </div>
                                <h3 class="serif">
                                    Your Balance Score:<br />
                                    <span style="color:#0140c9;font-size:1.5em">'.$balance.'</span>
                                </h3>
                            </td>
                            <td style="width:50%;text-align:center;">
                                <img width="300" src="'.get_site_url().'/wp-content/uploads/2020/09/Fit-House-for-powerpoint.png" />
                            </td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td class="wrapper">
                      <table>
                          <tr>
                              <td class="dimension-header" style="text-align:center;">
                                    <h2>Dimension Scores</h2>
                                    <div></div>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>

              <tr>
                  <td class="wrapper">
                      <table>
                          <tr class="dim-details">
                            <td width="25%" style="text-align:center;padding: 20px;border-bottom:1px solid #ffb100;transform: translateX(2px);">
                                <h3 class="dim-score" style="color:#ffb100;">COGNITIVE</h3>
                                <img src="'.get_site_url().'/wp-content/uploads/2020/09/cog.png" alt="dimension icon">
                            </td>
                            <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #ffb100;border-bottom:1px solid #ffb100;">
                                <h3 class="dim-score">
                                    <span id="cognitive-score" style="color:#ffb100;">'.$cognitive.'</span> 
                                    out of 20
                                </h3>
                                <div class="dim-copy">'.$cogText.'</div>
                            </td>
                          </tr>
                          <tr class="dim-details">
                            <td width="25%" style="text-align:center;padding: 20px;">
                                <img src="'.get_site_url().'/wp-content/uploads/2020/09/pillarYellow.png" alt="dimension icon">
                            </td>
                            <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #ffb100;">
                                <div class="colored-subdimemsions">
                                    <div style="color:#ff3a00">
                                        <span>LEARNING STRATEGIES ('.$learningStrategies.')</span>
                                        <p>'.get_field('subdimension_descriptions',$emailPageId)["learning_strategies"].'</p>
                                    </div>
                                    <div style="color:#ff4a00">
                                        <span>INTELLECTUAL ENGAGEMENT ('.$intellectualEngagement.')</span>
                                        <p>'.get_field('subdimension_descriptions',$emailPageId)["intellectual_engagement"].'</p>
                                    </div>
                                    <div style="color:#ff5a00">
                                        <span>EFFORTFUL CONTROL ('.$effortControl.')</span>
                                        <p>'.get_field('subdimension_descriptions',$emailPageId)["effortful_control"].'</p>
                                    </div>
                                    <div style="color:#ff6a00">
                                        <span>ATTENTION ('.$attention.')</span>
                                        <p>'.get_field('subdimension_descriptions',$emailPageId)["attention"].'</p>
                                    </div>
                                    <div style="color:#ff7e00">
                                        <span>AUTONOMY ('.$autonomy.')</span>
                                        <p>'.get_field('subdimension_descriptions',$emailPageId)["autonomy"].'</p>
                                    </div>
                                    <div style="color:#ff9400">
                                        <span>SOCIAL COGNITION ('.$autonomy.')</span>
                                        <p>'.get_field('subdimension_descriptions',$emailPageId)["social_cognition"].'</p>
                                    </div>
                                </div>
                            </td>
                          </tr>
                      </table>
                  </td>
              </tr>

              <tr>
                <td class="wrapper">
                    <table>
                        <tr class="dim-details">
                          <td width="25%" style="text-align:center;padding: 20px;border-bottom:1px solid #f26b3e;transform: translateX(2px);">
                              <h3 class="dim-score" style="color:#f26b3e;">PHYSICAL</h3>
                              <img src="'.get_site_url().'/wp-content/uploads/2020/09/phy.png" alt="dimension icon">
                          </td>
                          <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #f26b3e;border-bottom:1px solid #f26b3e;">
                              <h3 class="dim-score">
                                  <span id="physical-score" style="color:#f26b3e;">'.$physical.'</span> 
                                  out of 20
                              </h3>
                              <div class="dim-copy">'.$phyText.'</div>
                          </td>
                        </tr>
                        <tr class="dim-details">
                          <td width="25%" style="text-align:center;padding: 20px;">
                              <img src="'.get_site_url().'/wp-content/uploads/2020/09/pillarRed.png" alt="dimension icon">
                          </td>
                          <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #f26b3e;">
                              <div class="colored-subdimemsions">
                                  <div style="color:#a11500">
                                      <span>NUTRITION ('.$nutrition.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["nutrition"].'</p>
                                  </div>
                                  <div style="color:#b51800">
                                      <span>NUTRITIONAL KNOWLEDGE ('.$nutritionKnowledge.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["nutritional_knowledge"].'</p>
                                  </div>
                                  <div style="color:#c01a00">
                                      <span>ACTIVITY LEVEL ('.$activityLevel.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["activity_level"].'</p>
                                  </div>
                                  <div style="color:#cb1c00">
                                      <span>AEROBIC ACTIVITY ('.$aerobicActivity.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["aerobic_activity"].'</p>
                                  </div>
                                  <div style="color:#d72100">
                                      <span>STRENGTH TRAINING ('.$strengthTraining.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["strength_training"].'</p>
                                  </div>
                                  <div style="color:#e43304">
                                      <span>SLEEP HABITS ('.$sleepHabits.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["sleep_habits"].'</p>
                                  </div>
                                  <div style="color:#f26b3e">
                                      <span>SELF-IMAGE ('.$selfImage.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["self_image"].'</p>
                                  </div>
                              </div>
                          </td>
                        </tr>
                    </table>
                </td>
            </tr>

              <tr>
                <td class="wrapper">
                    <table>
                        <tr class="dim-details">
                          <td width="25%" style="text-align:center;padding: 20px;border-bottom:1px solid #68d566;transform: translateX(2px);">
                              <h3 class="dim-score" style="color:#68d566;">FINANCIAL</h3>
                              <img src="'.get_site_url().'/wp-content/uploads/2020/09/fin.png" alt="dimension icon">
                          </td>
                          <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #68d566;border-bottom:1px solid #68d566;">
                              <h3 class="dim-score">
                                  <span id="financial-score" style="color:#68d566;">'.$financial.'</span> 
                                  out of 20
                              </h3>
                              <div class="dim-copy">'.$finText.'</div>
                          </td>
                        </tr>
                        <tr class="dim-details">
                          <td width="25%" style="text-align:center;padding: 20px;">
                              <img src="'.get_site_url().'/wp-content/uploads/2020/09/pillarGreen.png" alt="dimension icon">
                          </td>
                          <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #68d566;">
                              <div class="colored-subdimemsions">
                                  <div style="color:#004c00">
                                      <span>LONG-TERM PERSPECTIVE ('.$longTerm.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["long-term_perspective"].'</p>
                                  </div>
                                  <div style="color:#005d00">
                                      <span>SHORT-TERM PERSPECTIVE ('.$shortTerm.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["short-term_perspective"].'</p>
                                  </div>
                                  <div style="color:#006e00">
                                      <span>REDUCE SADNESS ('.$reduceSadness.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["reduce_sadness"].'</p>
                                  </div>
                                  <div style="color:#008200">
                                      <span>INCREASE HAPPINESS ('.$increaseHappiness.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["increase_happiness"].'</p>
                                  </div>
                                  <div style="color:#009906">
                                      <span>NON-PECUNIARY ('.$nonPecuniary.')</span>
                                      <p>'.get_field('subdimension_descriptions',$emailPageId)["non_pecuniary"].'</p>
                                  </div>
                              </div>
                          </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
              <td class="wrapper">
                  <table>
                      <tr class="dim-details">
                        <td width="25%" style="text-align:center;padding: 20px;border-bottom:1px solid #08c6de;transform: translateX(2px);">
                            <h3 class="dim-score" style="color:#08c6de;">EMOTIONAL</h3>
                            <img src="'.get_site_url().'/wp-content/uploads/2020/09/emo.png" alt="dimension icon">
                        </td>
                        <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #08c6de;border-bottom:1px solid #08c6de;">
                            <h3 class="dim-score">
                                <span id="emotional-score" style="color:#08c6de;">'.$emotional.'</span> 
                                out of 20
                            </h3>
                            <div class="dim-copy">'.$emoText.'</div>
                        </td>
                      </tr>
                      <tr class="dim-details">
                        <td width="25%" style="text-align:center;padding: 20px;">
                            <img src="'.get_site_url().'/wp-content/uploads/2020/09/pillarTurq.png" alt="dimension icon">
                        </td>
                        <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #08c6de;">
                            <div class="colored-subdimemsions">
                                <div style="color:#00296f">
                                    <span>CURRENT EMOTIONAL HEALTH ('.$currentEmotionalHealth.')</span>
                                    <p>'.get_field('subdimension_descriptions',$emailPageId)["current_emotional_health"].'</p>
                                </div>
                                <div style="color:#00396f">
                                    <span>SELF-COMPASSION AND EMOTIONAL AWARENESS ('.$selfCompassion.')</span>
                                    <p>'.get_field('subdimension_descriptions',$emailPageId)["self_compassion_and_emotional_awareness"].'</p>
                                </div>
                                <div style="color:#004980">
                                    <span>STRESS RESILIENCE ('.$stressResilience.')</span>
                                    <p>'.get_field('subdimension_descriptions',$emailPageId)["stress_resilience"].'</p>
                                </div>
                                <div style="color:#005f92">
                                    <span>GRATITUDE AND POSITIVITY ('.$gratitudePositivity.')</span>
                                    <p>'.get_field('subdimension_descriptions',$emailPageId)["gratitude_and_positivity"].'</p>
                                </div>
                                <div style="color:#007aa9">
                                    <span>MINDSET ('.$mindset.')</span>
                                    <p>'.get_field('subdimension_descriptions',$emailPageId)["mindset"].'</p>
                                </div>
                                <div style="color:#009cc1">
                                    <span>SOCIAL ENGAGEMENT ('.$socialEngagement.')</span>
                                    <p>'.get_field('subdimension_descriptions',$emailPageId)["social_engagement"].'</p>
                                </div>
                            </div>
                        </td>
                      </tr>
                  </table>
              </td>
          </tr>

          <tr>
            <td class="wrapper">
                <table>
                    <tr class="dim-details">
                      <td width="25%" style="text-align:center;padding: 20px;border-bottom:1px solid #8e8ee7;transform: translateX(2px);">
                          <h3 class="dim-score" style="color:#8e8ee7;">SPIRITUAL</h3>
                          <img src="'.get_site_url().'/wp-content/uploads/2020/09/spi.png" alt="dimension icon">
                      </td>
                      <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #8e8ee7;border-bottom:1px solid #8e8ee7;">
                          <h3 class="dim-score">
                              <span id="spiritual-score" style="color:#8e8ee7;">'.$spiritual.'</span> 
                              out of 20
                          </h3>
                          <div class="dim-copy">'.$spiText.'</div>
                      </td>
                    </tr>
                    <tr class="dim-details">
                      <td width="25%" style="text-align:center;padding: 20px;">
                          <img src="'.get_site_url().'/wp-content/uploads/2020/09/pillarPurple.png" alt="dimension icon">
                      </td>
                      <td width="75%" style="text-align:left;padding: 20px;border-left:1px solid #8e8ee7;">
                          <div class="colored-subdimemsions">
                              <div style="color:#01188d">
                                  <span>CONNECTION ('.$connection.')</span>
                                  <p>'.get_field('subdimension_descriptions',$emailPageId)["connection"].'</p>
                              </div>
                              <div style="color:#02188d">
                                  <span>COMPASSION AND EMPATHY ('.$compassionEmpathy.')</span>
                                  <p>'.get_field('subdimension_descriptions',$emailPageId)["compassion_and_empathy"].'</p>
                              </div>
                              <div style="color:#071d9c">
                                  <span>FORGIVENESS ('.$forgiveness.')</span>
                                  <p>'.get_field('subdimension_descriptions',$emailPageId)["forgiveness"].'</p>
                              </div>
                              <div style="color:#1224ac">
                                  <span>PURPOSE ('.$purpose.')</span>
                                  <p>'.get_field('subdimension_descriptions',$emailPageId)["purpose"].'</p>
                              </div>
                              <div style="color:#2733be">
                                  <span>PRESENCE ('.$presence.')</span>
                                  <p>'.get_field('subdimension_descriptions',$emailPageId)["presence"].'</p>
                              </div>
                          </div>
                      </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td class="wrapper">
                <table>
                    <tr class="marketing-text">
                        <td width="25%" style="padding:20px;">'.$marketingImages.'</td>
                        <td width="75%" style="padding:20px;">'.$marketingText.'</td>
                    </tr>
                </table>
            </td>
        </tr>



            <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->

            <!-- START FOOTER -->
            <div class="footer">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-block">
                      <a href="mailto:jake@thefitexperience.com">Send us an email!</a>
                    <span class="apple-link">@2020 The FIT Experience</span>
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->

          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';

return $email_html;

}
