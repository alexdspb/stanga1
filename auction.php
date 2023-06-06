<?php

/*
 * The class Auction follows SOLID principles.
 * It has a single responsibility related to the auction functionality.
 * It can be extended to incorporate additional features.
 * */

class Auction
{
  public function __construct(
    private readonly float $reservePrice,
    private array $bids = []
  ) {}

  public function placeBid(string $buyer, float $price): void
  {
    $this->bids[] = [
      'buyer' => $buyer,
      'price' => $price,
    ];
  }

  public function findWinner(): array
  {
    $winnerBid = array_reduce($this->bids, function (array $carry, array $item) {
      if ($item['price'] < $this->reservePrice) {
        return $carry;
      }
      return $item['price'] > $carry['price'] ? $item : $carry;
    }, $this->bids[0]);

    $winnerPrice = array_reduce($this->bids, function (int $carry, array $item) use ($winnerBid) {
      if ($item['buyer'] === $winnerBid['buyer']) {
        return $carry;
      }
      return max($item['price'], $carry);
    }, $this->bids[0]['price']) ?? $this->reservePrice;

    return [
      'buyer' => $winnerBid['buyer'],
      'price' => $winnerPrice,
    ];
  }
}

$auction = new Auction(100);

$auction->placeBid('A', 110);
$auction->placeBid('A', 130);
$auction->placeBid('C', 125);
$auction->placeBid('D', 105);
$auction->placeBid('D', 115);
$auction->placeBid('D', 90);
$auction->placeBid('E', 132);
$auction->placeBid('E', 135);
$auction->placeBid('E', 140);

$winner = $auction->findWinner();

print("The winner is {$winner['buyer']} with the price {$winner['price']}\n");
