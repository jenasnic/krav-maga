<?php

namespace App\DataFixtures\Payment;

use App\DataFixtures\Factory\AdherentFactory;
use App\DataFixtures\Factory\Payment\AncvPaymentFactory;
use App\DataFixtures\Factory\Payment\CashPaymentFactory;
use App\DataFixtures\Factory\Payment\CheckPaymentFactory;
use App\DataFixtures\Factory\Payment\PassPaymentFactory;
use App\DataFixtures\Factory\Payment\TransferPaymentFactory;
use App\DataFixtures\Factory\SeasonFactory;
use App\DataFixtures\RegistrationFixtures;
use App\DataFixtures\SeasonFixtures;
use App\Entity\Adherent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use LogicException;
use Zenstruck\Foundry\Proxy;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $currentSeason = SeasonFactory::find(['active' => true]);

        /** @var Proxy<Adherent> $adherent */
        foreach (AdherentFactory::all() as $adherent) {
            $paymentCount = $this->faker->numberBetween(1, 4);
            $amount = 240 / $paymentCount;
            $paymentDate = $this->faker->dateTimeBetween('-1 month');
            while ($paymentCount-- > 0) {
                $type = $this->faker->randomElement(['ancv', 'cash', 'check', 'pass', 'transfer']);
                match ($type) {
                    'ancv' => AncvPaymentFactory::createOne([
                        'adherent' => $adherent->object(),
                        'season' => $currentSeason,
                        'amount' => $amount,
                        'date' => $paymentDate,
                    ]),
                    'cash' => CashPaymentFactory::createOne([
                        'adherent' => $adherent->object(),
                        'season' => $currentSeason,
                        'amount' => $amount,
                        'date' => $paymentDate,
                    ]),
                    'check' => CheckPaymentFactory::createOne([
                        'adherent' => $adherent->object(),
                        'season' => $currentSeason,
                        'amount' => $amount,
                        'date' => $paymentDate,
                    ]),
                    'pass' => PassPaymentFactory::createOne([
                        'adherent' => $adherent->object(),
                        'season' => $currentSeason,
                        'amount' => $amount,
                        'date' => $paymentDate,
                    ]),
                    'transfer' => TransferPaymentFactory::createOne([
                        'adherent' => $adherent->object(),
                        'season' => $currentSeason,
                        'amount' => $amount,
                        'date' => $paymentDate,
                    ]),
                    default => throw new LogicException('invalid type')
                };
            }
        }
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            RegistrationFixtures::class,
            SeasonFixtures::class,
        ];
    }
}
