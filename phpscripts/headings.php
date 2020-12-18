
<?php
	$text = '
		<h1>Заголовок h1</h1>
		<h2>Заголовок h2</h2>
		<h3>Заголовок h3</h3>
		<h4>Заголовок h4</h4>
		<h5>Заголовок h5</h5>
		<h6>Заголовок h6</h6>
		<h3>Название 2 книги</h3>
		<h6>Краткое описание 2 книги...</h6>
		<h3>Название 3 книги</h3>
		<h6>Краткое описание 3 книги...</h6>
		<h3>Название 4 книги</h3>
		<h6>Краткое описание 4 книги...</h6>
	';
    function createHeadings($text, $heading="Cодержание")
    // Создает содержание, и помещает текст
    {
        $Text=$text;
        preg_match_all("/<[hH]([1-6])>(.*?)<\/[hH][1-6]>/ui", $Text, $outArray);
?>
        <div class ="container ">
			<div class="texts-list">
				<h2><?php echo $heading; ?></h2>
				<ol>
<?php
					foreach ($outArray[2] as $i => $row)//перебор массива циклом, в котором на каждой итерации значение текущего элемента присваивается переменной
					//row и соотносится ключ текущего элемента с переменной i
					{
						if($outArray[1][$i]==1)
						{
							echo '<a class="ml-0" href="' .'#h-'. ++$i . '">' . $row . '</a><br>';
						}
						elseif($outArray[1][$i]==2)
						{
							echo '<a class="ml-1" href="' .'#h-'. ++$i . '">' . $row . '</a><br>';
						}
						elseif($outArray[1][$i]==3)
						{
							echo '<a class="ml-2" href="' .'#h-'. ++$i . '">' . $row . '</a><br>';
						}
						elseif($outArray[1][$i]==4)
						{
							echo '<a class="ml-3" href="' .'#h-'. ++$i . '">' . $row . '</a><br>';
						}
						elseif($outArray[1][$i]==5)
						{
							echo '<a class="ml-4" href="' .'#h-'. ++$i . '">' . $row . '</a><br>';
						}
						elseif($outArray[1][$i]==6)
						{
							echo '<a class="ml-5" href="' .'#h-'. ++$i . '">' . $row . '</a><br>';
						}
					}
?>
				</ol>
			</div>
		</div>
<?php
        foreach ($outArray[0] as $i => $row) 
		{
			$k=$i+1;
			$text = str_replace($row, '<h' . $outArray[1][$i] . ' id="h-'. $k . '">' . $outArray[2][$i] . '</h' . $outArray[1][$i] . '>', $text);//заменяет строку поиска row на строку замены(2 аргумент) в text
        } 
        return $text;
    }
	$text = createHeadings($text,'Оглавление списка книг для чтения');
?>