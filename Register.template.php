<?php
// After registration... all done ;).
function template_after()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Not much to see here, just a quick... "you're now registered!" or what have you.
	echo '
		<div id="registration_success">
			<div class="cat_bar">
				<h3 class="catbg">', $context['title'], '</h3>
			</div>
			<div class="windowbg">
				<span class="topslice"><span></span></span>
				<p class="content">', $context['description'], '</p>
                <h3>Att komma igång</h3>
                <h2>Presentation</h2>
                <p>
                    Vår Community erbjuder en del sätt att integrera med våra medlemmar utanför forumet. Vi ser gärna att man presenterar sig i tråden <a href="">Medlemssnack</a>.
                    Mängden information är frivilligt. De flesta vill veta vad man spelar. 
                </p>
                <h2>Spelkompisar</h2>
                <p>
                    Via tråden <a href="">Någon att spela med</a> annonserar vi efter spelvänner för en kväll eller för att klara en kampanj på legendary i coop. För att vi skall
                    veta att en eventuell Kompisförfrågan kommer från en Xboyzare ser vi gärna att ni har med xboyz.se i er XBL beskrivning på något sätt.
                </p>
                <h2>Spelkvällar</h2>
                <p>
                    Titt som tätt anordnas det spelkvällar, oftast i online multiplayer i privatlobby. Vanligast COD, Halo och dylikt. Håll koll på forumet för att haka på.
                </p>
                <h2>Twitter</h2>
                <p>
                    En del av våra mest aktiva besökare finns på Twitter och där pratas det vitt och brett om spel och väldigt ofta bokas det spelkvällar on-the-fly där. Vi har en
                    officiell lista över alla Xboyzare på Twitter. Den hittar ni här <a href="">Xboyz på Twitter</a>. Se också till att följa @XboyzSE.
                </p>
				<span class="botslice"><span></span></span>
			</div>
            
		</div>';
}

?>

