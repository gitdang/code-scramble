<?php

require 'codescramble-database.php';

function angle() {
	$angles = [-30, -25, -20, -15, -10, -5, 5, 10, 15, 20, 25, 30];
	return $angles[rand(1, sizeof($angles)) - 1];
}

function shuffle_code_divs($code_array) {
	shuffle($code_array);
	return $code_array;
}

function wrap_code_in_pre($code) {
	$pre = [];
	foreach ($code as $key => $line) {
		$pre[] = "<pre class='scrambled ui-draggable ui-widget-content' style='transform: rotate(" . angle() . "deg);' data-seq='$key'>" . $line . "</pre>";
	}
	return $pre;
}

$coding_task = get_code_from_database();
$description = $coding_task['description'];
$ordered_code = wrap_code_in_pre($coding_task['code']);
$scrambled_code = $ordered_code;
shuffle($scrambled_code);

?>
<!doctype html>
<html>
  <head>
    <title>Code Scrambler (Proof of Concept)</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <style>
      .code {
        font-family: monospace;
      }

      .code .scrambled {
        background-color: pink;
        border-style: solid;
        border-width: 1px;
        border-color: black;
        display: inline-block;
        margin: 1px;
        padding: 5px;
      }

      .code .target {
        border-collapse: collapse;
        border-width: 1px;
        border-color: yellow;
        margin: 3px;
        padding: 1px;
      }

      .code .ordered {
        border: none;
        padding: 1px;
        margin: 0 0 0 0;
        display: block;
      }

      .ui-droppable-hover {
        background-color: orange;
      }

      .foot {
        width: 33%;
      }

    </style>
  </head>
  <body>
    <nav class="navbar bg-info">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="javascript:window.location.reload()">New Scramble</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:reportResults()">Check Answer</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:restoreAll()">Blaze</a>
        </li>
      </ul>
    </nav>

    <div style="display:flex;">

      <div class="scrambled">
        <h2>SCRAMBLED CODE</h2>
        <p>Drag the scrambled lines of a short program to the blanks below, to put the code in the right order. Double-click on an ordered one to put it back.</p>
        <div class="code">
          <?php
foreach ($scrambled_code as $line) {
	echo $line;
}
?>
        </div>
      </div>

      <div class="ordered">
        <h2>ORDERED CODE</h2>
        <p><?=$description?></p>
        <div class="code"><?php
// I loop based on the ordered_code, because
// In the future, there may be unused (red-
// herring) LOC in the scrambled code, so the
// number of targets could be less than the
// number of scrambles codes.
foreach ($ordered_code as $seq => $line) {
	echo "<pre class='target ui-droppable ui-widget-header' data-seq='$seq' ondblclick='restore(this)'>&nbsp;</pre>";
}
?></div>

    </div>

<!--     <div class="foot">
      <p>This is here so that the dragging isn't a PITA. Depending where the pointer is when the code is dragged, the browser might try to scroll, which makes it hard to drop it on the desired target.</p>
    </div>
 -->
    <script type="text/javascript">

      let restore = function restore(t) {
        let code_seq = $(t).attr('data-scrambled-seq')
        if (code_seq >= 0) {
          let line_seq = $(t).attr('data-seq')
          $('.ordered .code [data-seq="' + line_seq + '"]')
            .attr('data-scrambled-seq', -1)
            .removeClass("ordered")
            .html("&nbsp;") // A space keeps the HTML from collapsing.
          $('.scrambled .code [data-seq="' + code_seq + '"]').show()
        }
      }

      let restoreAll = function restoreAll() {
        for (let i = $('.ordered pre').length - 1; i >= 0; --i) {
          restore($('.ordered pre')[i])
        }
      }

      let checkAnswer = function checkAnswer() {
        let orderedCells = $('.ordered pre')
        let numOrdered = 0

        for (let i = orderedCells.length - 1; i >= 0; --i) {
          let cell = $(orderedCells[i])
          if (cell.attr('data-seq') == cell.attr('data-scrambled-seq')) {
            numOrdered += 1
          }
        }

        return [numOrdered, orderedCells.length]
      }

      let reportResults = function reportResults() {
        let results = checkAnswer()
        let numOrdered = results[0]
        let allOrdered = results[1]
        let ratio = Math.floor(10 * numOrdered / allOrdered)

        if (ratio < 5) {
          alert("Keep trying. There's still work to be done.")
        } else if (ratio < 6) {
          alert("You're halfway there!")
        } else if (ratio < 8) {
          alert("You're getting there!")
        } else if (ratio < 10) {
          alert("Ooh, you're so close!")
        } else {
          alert("Congratulations! You got them all right!")
        }
      }

      $(".ui-draggable").draggable()

      $(".ordered .target").droppable({

        'accept': '.ui-draggable',

        'tolerance': 'pointer',

        'drop': function(event, ui) {
          dragged = ui.draggable[0]

          // 1. Restore any code already at this position.
          restore(this)

          // 2. Place the source in the target position.
          $(this).attr('data-scrambled-seq', $(dragged).attr('data-seq'))
          $(this).addClass("ordered")
          $(this).text($(dragged).text())

          // 3. Put the dragged code back in place, in case it's needed later.
          $(dragged).hide()
          $(dragged).draggable({ revert: true })

        },
      })
    </script>
  </body>
</html>
