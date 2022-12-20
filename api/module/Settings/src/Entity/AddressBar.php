<?php

namespace Settings\Entity;

/**
 * AddressBar
 */
class AddressBar
{
    const TYPE_BAR_CUSTOMER = 'addressBarCustomer';
    const TYPE_BAR_USER = 'addressBarUser';
    const TYPE_BAR_REGION_DIFFERENT = 'customerRegionDifferent';
    const TYPE_BAR_REGION_SAME = 'customerRegionSame';

    const TAG_REGION_USERS = 'użytkownicy z regionu';
    const TAG_REGION_SUMMARY = 'podsumowanie regionu użytkownika';

    const TAG_CUSTOMER_NAME = 'nazwa klienta';
    const TAG_CUSTOMER_STREET = 'ulica klienta';
    const TAG_CUSTOMER_STREET_NUMBER = 'nr ulicy klienta';
    const TAG_CUSTOMER_LOCAL = 'nr lokalu klienta';
    const TAG_CUSTOMER_ZIPCODE = 'kod pocztowy klienta';
    const TAG_CUSTOMER_CITY = 'miejscowość klienta';
    const TAG_CUSTOMER_PHONE = 'telefon klienta';
    const TAG_CUSTOMER_EMAIL = 'email klienta';

    const TAG_CUSTOMER_CHAIN = 'sieć klienta';
    const TAG_CUSTOMER_SUBCHAIN = 'podsieć klienta';
    const TAG_CUSTOMER_REGION = 'region klienta';
    const TAG_CUSTOMER_SUBREGION = 'subregion klienta';
    const TAG_CUSTOMER_FORMAT = 'format klienta';
    const TAG_CUSTOMER_SUBFORMAT = 'podformat klienta';
    const TAG_CUSTOMER_SALE_STAGE = 'etap sprzedaży klienta';
    const TAG_CUSTOMER_STATUS = 'status klienta';

    const TAG_USER_NAME = 'imię i nazwisko użytkownika';

    const TAG_PAYER = 'płatnik';

    public static function getTags(): array
    {
        return [
            self::TAG_REGION_USERS, self::TAG_REGION_SUMMARY, self::TAG_CUSTOMER_NAME, self::TAG_CUSTOMER_STREET,
            self::TAG_CUSTOMER_STREET_NUMBER, self::TAG_CUSTOMER_LOCAL, self::TAG_CUSTOMER_ZIPCODE,
            self::TAG_CUSTOMER_CITY, self::TAG_CUSTOMER_CHAIN, self::TAG_CUSTOMER_SUBCHAIN, self::TAG_CUSTOMER_REGION,
            self::TAG_CUSTOMER_PHONE, self::TAG_CUSTOMER_EMAIL,
            self::TAG_CUSTOMER_SUBREGION, self::TAG_CUSTOMER_FORMAT, self::TAG_CUSTOMER_SUBFORMAT,
            self::TAG_CUSTOMER_SALE_STAGE, self::TAG_CUSTOMER_STATUS, self::TAG_USER_NAME,
            self::TAG_PAYER,
        ];
    }

    /**
     * @var integer
     */
    private $name;

    /**
     * @var string
     */
    private $value;


    /**
     * Set name
     *
     * @param integer $name
     *
     * @return AddressBar
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return integer
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return AddressBar
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

