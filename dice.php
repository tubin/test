<?php 

function out($message)
{
    print $message.PHP_EOL;
}

function generateHand()
{
    return str_split(substr(str_shuffle(str_repeat('123456', mt_rand(1,50))),1,5));
}

function analyzeHand(array $hand)
{   
    $shifted = array_map(function($dice) {
        return ($dice+1)%13;
    }, $hand);

    $distance = min([max($hand)-min($hand), max($shifted)-min($shifted)]);

    $groups = array_map(function($dice, $i) use($hand) {
        return count(array_filter($hand, function($dice) use($i) {
            return $dice == $i;
        }));
    }, $hand, [1, 2, 3, 4, 5, 6]);

    usort($groups, function($a, $b) {
        return $b-$a;
    });

    sort($hand);

    if($groups[0] == 5) return 'Покер';
    else if($groups[0] == 4) return 'Каре';
    else if($groups[0] === 3 && $groups[1] === 2) return 'Фул хаус';
    else if($groups[0] === 1 && $distance < 5) return 'Большой стрит';
    else if(($hand[0]+1==$hand[1] && $hand[1]+1==$hand[2] && $hand[2]+1==$hand[3]) || ($hand[1]+1==$hand[2] && $hand[2]+1==$hand[3] && $hand[3]+1==$hand[4])) return 'Малый стрит';
    else if($groups[0] == 3) return 'Сет';
    else if ($groups[0] === 2 && $groups[1] === 2) return 'Две пары';
    else if($groups[0] == 2) return 'Пара';
    else return 'Шанс';
}

$options = getopt("", ["count:"]);

for($i = 0; $i < ($options['count'] ?? 1); $i++) {
    $hand = generateHand();
    out('Пример: '.implode('',$hand).', Результат: '.analyzeHand($hand));
}