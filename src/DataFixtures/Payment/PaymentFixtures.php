<?php

namespace App\DataFixtures\Payment;

use App\DataFixtures\Factory\Payment\AncvPaymentFactory;
use App\DataFixtures\Factory\Payment\CashPaymentFactory;
use App\DataFixtures\Factory\Payment\CheckPaymentFactory;
use App\DataFixtures\Factory\Payment\PassPaymentFactory;
use App\DataFixtures\Factory\Payment\TransferPaymentFactory;
use App\DataFixtures\Factory\RegistrationFactory;
use App\DataFixtures\Factory\SeasonFactory;
use App\DataFixtures\RegistrationFixtures;
use App\DataFixtures\SeasonFixtures;
use App\Entity\Payment\PriceOption;
use App\Entity\Registration;
use App\Entity\Season;
use App\Helper\FloatHelper;
use DateInterval;
use DateTime;
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
        $season2020 = SeasonFactory::find(['label' => SeasonFixtures::SEASON_2020])->object();
        $season2021 = SeasonFactory::find(['label' => SeasonFixtures::SEASON_2021])->object();
        $season2022 = SeasonFactory::find(['label' => SeasonFixtures::SEASON_2022])->object();

        /** @var Proxy<Registration> $registration */
        foreach (RegistrationFactory::all() as $registration) {
            $registration = $registration->object();

            if (SeasonFixtures::SEASON_2020 === $registration->getSeason()->getLabel()) {
                $this->createPaymentsForSeason($registration, $season2020, true);
            } elseif (SeasonFixtures::SEASON_2021 === $registration->getSeason()->getLabel()) {
                if ($this->faker->boolean(80)) {
                    $this->createPaymentsForSeason($registration, $season2020, true);
                }
                $this->createPaymentsForSeason($registration, $season2021, true);
            } else {
                if ($this->faker->boolean(80)) {
                    $this->createPaymentsForSeason($registration, $season2020, true);
                    $this->createPaymentsForSeason($registration, $season2021, true);
                }
                $this->createPaymentsForSeason($registration, $season2022, $this->faker->boolean(60));
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

    protected function createPaymentsForSeason(Registration $registration, Season $season, bool $sold): void
    {
        /** @var PriceOption $priceOption */
        $priceOption = $registration->getPriceOption();
        $amount = $priceOption->getAmount();

        /** @var DateTime $startDate */
        $startDate = $season->getStartDate();
        $paymentDate = $startDate->add(DateInterval::createFromDateString('+1 month'));

        $paymentAttributes = [
            'adherent' => $registration->getAdherent(),
            'season' => $season,
            'date' => $paymentDate,
        ];

        if ($registration->isUsePass15()) {
            $amount -= 15;
            $paymentAttributes['amount'] = 15;
            PassPaymentFactory::createOne($paymentAttributes);
        }

        if ($registration->isUsePass50()) {
            $amount -= 50;
            $paymentAttributes['amount'] = 50;
            PassPaymentFactory::createOne($paymentAttributes);
        }

        do {
            $newAmount = ($amount < 100 || $this->faker->boolean()) ? $amount : 60;
            $paymentDate = $paymentDate->add(DateInterval::createFromDateString('+1 month'));

            if (!$sold && FloatHelper::equals($amount, $newAmount)) {
                break;
            }

            $paymentAttributes['amount'] = $newAmount;
            $paymentAttributes['date'] = $paymentDate;
            $this->createPayment($paymentAttributes);

            $amount -= $newAmount;
        } while (FloatHelper::greater($amount, 0));
    }

    /**
     * @param array<string, mixed> $attributes
     */
    protected function createPayment(array $attributes): void
    {
        $type = $this->faker->randomElement(['ancv', 'cash', 'check', 'transfer']);

        match ($type) {
            'ancv' => AncvPaymentFactory::createOne($attributes),
            'cash' => CashPaymentFactory::createOne($attributes),
            'check' => CheckPaymentFactory::createOne($attributes),
            'transfer' => TransferPaymentFactory::createOne($attributes),
            default => throw new LogicException('invalid type')
        };
    }
}