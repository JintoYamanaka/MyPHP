<?php

namespace MyApp;

class Calendar {
  public $prev;
  public $next;
  public $yearMonth;
  private $_thisMonth;

  public function __construct() {
    try {
      if (!isset($_GET['t']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])) {
        throw new \Exception();
      }
      $this->_thisMonth = new \DateTime($_GET['t']);
    } catch (\Exception $e) {
      $this->_thisMonth = new \DateTime('first day of this month');
    }
    $this->prev = $this->_createPrevLink();
    $this->next = $this->_createNextLink();
    $this->yearMonth = $this->_thisMonth->format('F Y');
  }

  private function _createPrevLink() {
    $dt = clone $this->_thisMonth;
    return $dt->modify('-1 month')->format('Y-m');
  }

  private function _createNextLink() {
    $dt = clone $this->_thisMonth;
    return $dt->modify('+1 month')->format('Y-m');
  }
  
  public function show() {
    $tail = $this->_getTail();
    $body = $this->_getBody();
    $head = $this->_getHead();
    $html = '<tr>' . $tail . $body . $head . '</tr>';
    echo $html;
  }

  private function _getTail() {
    $tail = '';
    $lastDayOfPrevMonth = new \DateTime('last day of ' . $this->yearMonth . ' -1 month');
    while ($lastDayOfPrevMonth->format('w') < 6) {
      $tail = sprintf('<td class="gray">%d</td>', $lastDayOfPrevMonth->format('d')) . $tail;
      $lastDayOfPrevMonth->sub(new \DateInterval('P1D'));
    }
    return $tail;
  }

  private function _getBody() {
    $body = '';
    $period = new \DatePeriod(
      new \DateTime('first day of ' . $this->yearMonth),
      new \DateInterval('P1D'),
      new \DateTime('first day of ' . $this->yearMonth . ' +1 month')
    );
    $today = new \DateTime('today');
    foreach ($period as $day) {
      if ($day->format('w') === '0') { $body .= '</tr><tr>'; }
      $todayClass = ($day->format('Y-m-d') === $today->format('Y-m-d')) ? 'today' : '';
      $body .= sprintf('<td class="youbi_%d %s">%d</td>', $day->format('w'), $todayClass, $day->format('d'));
    }
    return $body;
  }

  private function _getHead() {
    $head = '';
    $firstDayOfNextMonth = new \DateTime('first day of ' . $this->yearMonth . ' +1 month');
    while ($firstDayOfNextMonth->format('w') > 0) {
      $head .= sprintf('<td class="gray">%d</td>', $firstDayOfNextMonth->format('d'));
      $firstDayOfNextMonth->add(new \DateInterval('P1D'));
    }
    return $head;
  }
}

/*
try {
  if (!isset($_GET['t']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])) {
    throw new Exception();
  }
  $thisMonth = new DateTime($_GET['t']);
} catch (Exception $e) {
  $thisMonth = new DateTime('first day of this month');
}
// var_dump($thisMonth);
// exit;

$dt = clone $thisMonth;
$prev = $dt->modify('-1 month')->format('Y-m');
$dt = clone $thisMonth;
$next = $dt->modify('+1 month')->format('Y-m');

// $t = '2015-08';
// $thisMonth = new DateTime($t); // 2015-08-01
$yearMonth = $thisMonth->format('F Y');    // Fは月の名前 Yは年

$tail = '';    // 前の月の終わり
$lastDayOfPrevMonth = new DateTime('last day of ' . $yearMonth . ' -1 month');
while ($lastDayOfPrevMonth->format('w') < 6) {
  $tail = sprintf('<td class="gray">%d</td>', $lastDayOfPrevMonth->format('d')) . $tail;
  $lastDayOfPrevMonth->sub(new DateInterval('P1D'));   // 1日ずつ引く
}

$body = '';
$period = new DatePeriod(   // 特定の期間の日付オブジェクトを作る
  new DateTime('first day of ' . $yearMonth),  // 期間の最初の日
  new DateInterval('P1D'),   // 間隔（ここでは1） Period 1 Day → P1D
  new DateTime('first day of ' . $yearMonth . ' +1 month')  // 期間の終わり
);

$today = new DateTime('today');
foreach ($period as $day) {

  if ($day->format('w') == 0) { $body .= "</tr><tr>"; }  // 日曜だったら行替え
  // $body .= sprintf('<td class="youbi_%d">%d</td>', $day->format('w'), $day->format('d'));  // wやdは、php.netのdate()を確認
  $todayClass = ($day->format('Y-m-d') === $today->format('Y-m-d')) ? 'today' : '';
  $body .= sprintf('<td class="youbi_%d %s">%d</td>', $day->format('w'), $todayClass, $day->format('d'));
}

$head = '';   // 次の月のはじめ
$firstDayOfNextMonth = new DateTime('first day of ' . $yearMonth . ' +1 month');
while ($firstDayOfNextMonth->format('w') > 0) {
  $head .= sprintf('<td class="gray">%d</td>', $firstDayOfNextMonth->format('d'));
  $firstDayOfNextMonth->add(new DateInterval('P1D'));   // 1日ずつ進める
}

$html = '<tr>' . $tail . $body . $head . '</tr>';
*/
?>
