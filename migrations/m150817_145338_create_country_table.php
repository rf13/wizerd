<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_145338_create_country_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('country', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'iso_code_2' => Schema::TYPE_STRING . '(2) NOT NULL',
            'iso_code_3' => Schema::TYPE_STRING . '(3) NOT NULL'
        ], $tableOptions);

        $this->batchInsert('country', ['name', 'iso_code_2', 'iso_code_3'], [
            ['Afghanistan', 'AF', 'AFG'],
            ['Albania', 'AL', 'ALB'],
            ['Algeria', 'DZ', 'DZA'],
            ['American Samoa', 'AS', 'ASM'],
            ['Andorra', 'AD', 'AND'],
            ['Angola', 'AO', 'AGO'],
            ['Anguilla', 'AI', 'AIA'],
            ['Antarctica', 'AQ', 'ATA'],
            ['Antigua and Barbuda', 'AG', 'ATG'],
            ['Argentina', 'AR', 'ARG'],
            ['Armenia', 'AM', 'ARM'],
            ['Aruba', 'AW', 'ABW'],
            ['Australia', 'AU', 'AUS'],
            ['Austria', 'AT', 'AUT'],
            ['Azerbaijan', 'AZ', 'AZE'],
            ['Bahamas', 'BS', 'BHS'],
            ['Bahrain', 'BH', 'BHR'],
            ['Bangladesh', 'BD', 'BGD'],
            ['Barbados', 'BB', 'BRB'],
            ['Belarus', 'BY', 'BLR'],
            ['Belgium', 'BE', 'BEL'],
            ['Belize', 'BZ', 'BLZ'],
            ['Benin', 'BJ', 'BEN'],
            ['Bermuda', 'BM', 'BMU'],
            ['Bhutan', 'BT', 'BTN'],
            ['Bolivia', 'BO', 'BOL'],
            ['Bosnia and Herzegowina', 'BA', 'BIH'],
            ['Botswana', 'BW', 'BWA'],
            ['Bouvet Island', 'BV', 'BVT'],
            ['Brazil', 'BR', 'BRA'],
            ['British Indian Ocean Territory', 'IO', 'IOT'],
            ['Brunei Darussalam', 'BN', 'BRN'],
            ['Bulgaria', 'BG', 'BGR'],
            ['Burkina Faso', 'BF', 'BFA'],
            ['Burundi', 'BI', 'BDI'],
            ['Cambodia', 'KH', 'KHM'],
            ['Cameroon', 'CM', 'CMR'],
            ['Canada', 'CA', 'CAN'],
            ['Cape Verde', 'CV', 'CPV'],
            ['Cayman Islands', 'KY', 'CYM'],
            ['Central African Republic', 'CF', 'CAF'],
            ['Chad', 'TD', 'TCD'],
            ['Chile', 'CL', 'CHL'],
            ['China', 'CN', 'CHN'],
            ['Christmas Island', 'CX', 'CXR'],
            ['Cocos (Keeling) Islands', 'CC', 'CCK'],
            ['Colombia', 'CO', 'COL'],
            ['Comoros', 'KM', 'COM'],
            ['Congo', 'CG', 'COG'],
            ['Cook Islands', 'CK', 'COK'],
            ['Costa Rica', 'CR', 'CRI'],
            ['Cote D\'Ivoire', 'CI', 'CIV'],
            ['Croatia', 'HR', 'HRV'],
            ['Cuba', 'CU', 'CUB'],
            ['Cyprus', 'CY', 'CYP'],
            ['Czech Republic', 'CZ', 'CZE'],
            ['Denmark', 'DK', 'DNK'],
            ['Djibouti', 'DJ', 'DJI'],
            ['Dominica', 'DM', 'DMA'],
            ['Dominican Republic', 'DO', 'DOM'],
            ['East Timor', 'TP', 'TMP'],
            ['Ecuador', 'EC', 'ECU'],
            ['Egypt', 'EG', 'EGY'],
            ['El Salvador', 'SV', 'SLV'],
            ['Equatorial Guinea', 'GQ', 'GNQ'],
            ['Eritrea', 'ER', 'ERI'],
            ['Estonia', 'EE', 'EST'],
            ['Ethiopia', 'ET', 'ETH'],
            ['Falkland Islands (Malvinas)', 'FK', 'FLK'],
            ['Faroe Islands', 'FO', 'FRO'],
            ['Fiji', 'FJ', 'FJI'],
            ['Finland', 'FI', 'FIN'],
            ['France', 'FR', 'FRA'],
            ['France, Metropolitan', 'FX', 'FXX'],
            ['French Guiana', 'GF', 'GUF'],
            ['French Polynesia', 'PF', 'PYF'],
            ['French Southern Territories', 'TF', 'ATF'],
            ['Gabon', 'GA', 'GAB'],
            ['Gambia', 'GM', 'GMB'],
            ['Georgia', 'GE', 'GEO'],
            ['Germany', 'DE', 'DEU'],
            ['Ghana', 'GH', 'GHA'],
            ['Gibraltar', 'GI', 'GIB'],
            ['Greece', 'GR', 'GRC'],
            ['Greenland', 'GL', 'GRL'],
            ['Grenada', 'GD', 'GRD'],
            ['Guadeloupe', 'GP', 'GLP'],
            ['Guam', 'GU', 'GUM'],
            ['Guatemala', 'GT', 'GTM'],
            ['Guinea', 'GN', 'GIN'],
            ['Guinea-bissau', 'GW', 'GNB'],
            ['Guyana', 'GY', 'GUY'],
            ['Haiti', 'HT', 'HTI'],
            ['Heard and Mc Donald Islands', 'HM', 'HMD'],
            ['Honduras', 'HN', 'HND'],
            ['Hong Kong', 'HK', 'HKG'],
            ['Hungary', 'HU', 'HUN'],
            ['Iceland', 'IS', 'ISL'],
            ['India', 'IN', 'IND'],
            ['Indonesia', 'ID', 'IDN'],
            ['Iran (Islamic Republic of)', 'IR', 'IRN'],
            ['Iraq', 'IQ', 'IRQ'],
            ['Ireland', 'IE', 'IRL'],
            ['Israel', 'IL', 'ISR'],
            ['Italy', 'IT', 'ITA'],
            ['Jamaica', 'JM', 'JAM'],
            ['Japan', 'JP', 'JPN'],
            ['Jordan', 'JO', 'JOR'],
            ['Kazakhstan', 'KZ', 'KAZ'],
            ['Kenya', 'KE', 'KEN'],
            ['Kiribati', 'KI', 'KIR'],
            ['North Korea', 'KP', 'PRK'],
            ['Korea, Republic of', 'KR', 'KOR'],
            ['Kuwait', 'KW', 'KWT'],
            ['Kyrgyzstan', 'KG', 'KGZ'],
            ['Lao People\'s Democratic Republic', 'LA', 'LAO'],
            ['Latvia', 'LV', 'LVA'],
            ['Lebanon', 'LB', 'LBN'],
            ['Lesotho', 'LS', 'LSO'],
            ['Liberia', 'LR', 'LBR'],
            ['Libyan Arab Jamahiriya', 'LY', 'LBY'],
            ['Liechtenstein', 'LI', 'LIE'],
            ['Lithuania', 'LT', 'LTU'],
            ['Luxembourg', 'LU', 'LUX'],
            ['Macau', 'MO', 'MAC'],
            ['Macedonia', 'MK', 'MKD'],
            ['Madagascar', 'MG', 'MDG'],
            ['Malawi', 'MW', 'MWI'],
            ['Malaysia', 'MY', 'MYS'],
            ['Maldives', 'MV', 'MDV'],
            ['Mali', 'ML', 'MLI'],
            ['Malta', 'MT', 'MLT'],
            ['Marshall Islands', 'MH', 'MHL'],
            ['Martinique', 'MQ', 'MTQ'],
            ['Mauritania', 'MR', 'MRT'],
            ['Mauritius', 'MU', 'MUS'],
            ['Mayotte', 'YT', 'MYT'],
            ['Mexico', 'MX', 'MEX'],
            ['Micronesia, Federated States of', 'FM', 'FSM'],
            ['Moldova, Republic of', 'MD', 'MDA'],
            ['Monaco', 'MC', 'MCO'],
            ['Mongolia', 'MN', 'MNG'],
            ['Montserrat', 'MS', 'MSR'],
            ['Morocco', 'MA', 'MAR'],
            ['Mozambique', 'MZ', 'MOZ'],
            ['Myanmar', 'MM', 'MMR'],
            ['Namibia', 'NA', 'NAM'],
            ['Nauru', 'NR', 'NRU'],
            ['Nepal', 'NP', 'NPL'],
            ['Netherlands', 'NL', 'NLD'],
            ['Netherlands Antilles', 'AN', 'ANT'],
            ['New Caledonia', 'NC', 'NCL'],
            ['New Zealand', 'NZ', 'NZL'],
            ['Nicaragua', 'NI', 'NIC'],
            ['Niger', 'NE', 'NER'],
            ['Nigeria', 'NG', 'NGA'],
            ['Niue', 'NU', 'NIU'],
            ['Norfolk Island', 'NF', 'NFK'],
            ['Northern Mariana Islands', 'MP', 'MNP'],
            ['Norway', 'NO', 'NOR'],
            ['Oman', 'OM', 'OMN'],
            ['Pakistan', 'PK', 'PAK'],
            ['Palau', 'PW', 'PLW'],
            ['Panama', 'PA', 'PAN'],
            ['Papua New Guinea', 'PG', 'PNG'],
            ['Paraguay', 'PY', 'PRY'],
            ['Peru', 'PE', 'PER'],
            ['Philippines', 'PH', 'PHL'],
            ['Pitcairn', 'PN', 'PCN'],
            ['Poland', 'PL', 'POL'],
            ['Portugal', 'PT', 'PRT'],
            ['Puerto Rico', 'PR', 'PRI'],
            ['Qatar', 'QA', 'QAT'],
            ['Reunion', 'RE', 'REU'],
            ['Romania', 'RO', 'ROM'],
            ['Russian Federation', 'RU', 'RUS'],
            ['Rwanda', 'RW', 'RWA'],
            ['Saint Kitts and Nevis', 'KN', 'KNA'],
            ['Saint Lucia', 'LC', 'LCA'],
            ['Saint Vincent and the Grenadines', 'VC', 'VCT'],
            ['Samoa', 'WS', 'WSM'],
            ['San Marino', 'SM', 'SMR'],
            ['Sao Tome and Principe', 'ST', 'STP'],
            ['Saudi Arabia', 'SA', 'SAU'],
            ['Senegal', 'SN', 'SEN'],
            ['Seychelles', 'SC', 'SYC'],
            ['Sierra Leone', 'SL', 'SLE'],
            ['Singapore', 'SG', 'SGP'],
            ['Slovak Republic', 'SK', 'SVK'],
            ['Slovenia', 'SI', 'SVN'],
            ['Solomon Islands', 'SB', 'SLB'],
            ['Somalia', 'SO', 'SOM'],
            ['South Africa', 'ZA', 'ZAF'],
            ['South Georgia &amp; South Sandwich Islands', 'GS', 'SGS'],
            ['Spain', 'ES', 'ESP'],
            ['Sri Lanka', 'LK', 'LKA'],
            ['St. Helena', 'SH', 'SHN'],
            ['St. Pierre and Miquelon', 'PM', 'SPM'],
            ['Sudan', 'SD', 'SDN'],
            ['Suriname', 'SR', 'SUR'],
            ['Svalbard and Jan Mayen Islands', 'SJ', 'SJM'],
            ['Swaziland', 'SZ', 'SWZ'],
            ['Sweden', 'SE', 'SWE'],
            ['Switzerland', 'CH', 'CHE'],
            ['Syrian Arab Republic', 'SY', 'SYR'],
            ['Taiwan', 'TW', 'TWN'],
            ['Tajikistan', 'TJ', 'TJK'],
            ['Tanzania, United Republic of', 'TZ', 'TZA'],
            ['Thailand', 'TH', 'THA'],
            ['Togo', 'TG', 'TGO'],
            ['Tokelau', 'TK', 'TKL'],
            ['Tonga', 'TO', 'TON'],
            ['Trinidad and Tobago', 'TT', 'TTO'],
            ['Tunisia', 'TN', 'TUN'],
            ['Turkey', 'TR', 'TUR'],
            ['Turkmenistan', 'TM', 'TKM'],
            ['Turks and Caicos Islands', 'TC', 'TCA'],
            ['Tuvalu', 'TV', 'TUV'],
            ['Uganda', 'UG', 'UGA'],
            ['Ukraine', 'UA', 'UKR'],
            ['United Arab Emirates', 'AE', 'ARE'],
            ['United Kingdom', 'GB', 'GBR'],
            ['United States', 'US', 'USA'],
            ['United States Minor Outlying Islands', 'UM', 'UMI'],
            ['Uruguay', 'UY', 'URY'],
            ['Uzbekistan', 'UZ', 'UZB'],
            ['Vanuatu', 'VU', 'VUT'],
            ['Vatican City State (Holy See)', 'VA', 'VAT'],
            ['Venezuela', 'VE', 'VEN'],
            ['Viet Nam', 'VN', 'VNM'],
            ['Virgin Islands (British)', 'VG', 'VGB'],
            ['Virgin Islands (U.S.)', 'VI', 'VIR'],
            ['Wallis and Futuna Islands', 'WF', 'WLF'],
            ['Western Sahara', 'EH', 'ESH'],
            ['Yemen', 'YE', 'YEM'],
            ['Yugoslavia', 'YU', 'YUG'],
            ['Democratic Republic of Congo', 'CD', 'COD'],
            ['Zambia', 'ZM', 'ZMB'],
            ['Zimbabwe', 'ZW', 'ZWE']
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('country');
    }
}