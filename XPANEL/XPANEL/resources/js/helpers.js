const country_data = [
    { code: "AF", code3: "AFG", name: "Afghanistan", number: "004" },
    { code: "AL", code3: "ALB", name: "Albania", number: "008" },
    { code: "DZ", code3: "DZA", name: "Algeria", number: "012" },
    { code: "AS", code3: "ASM", name: "American Samoa", number: "016" },
    { code: "AD", code3: "AND", name: "Andorra", number: "020" },
    { code: "AO", code3: "AGO", name: "Angola", number: "024" },
    { code: "AI", code3: "AIA", name: "Anguilla", number: "660" },
    { code: "AQ", code3: "ATA", name: "Antarctica", number: "010" },
    { code: "AG", code3: "ATG", name: "Antigua and Barbuda", number: "028" },
    { code: "AR", code3: "ARG", name: "Argentina", number: "032" },
    { code: "AM", code3: "ARM", name: "Armenia", number: "051" },
    { code: "AW", code3: "ABW", name: "Aruba", number: "533" },
    { code: "AU", code3: "AUS", name: "Australia", number: "036" },
    { code: "AT", code3: "AUT", name: "Austria", number: "040" },
    { code: "AZ", code3: "AZE", name: "Azerbaijan", number: "031" },
    { code: "BS", code3: "BHS", name: "Bahamas (the)", number: "044" },
    { code: "BH", code3: "BHR", name: "Bahrain", number: "048" },
    { code: "BD", code3: "BGD", name: "Bangladesh", number: "050" },
    { code: "BB", code3: "BRB", name: "Barbados", number: "052" },
    { code: "BY", code3: "BLR", name: "Belarus", number: "112" },
    { code: "BE", code3: "BEL", name: "Belgium", number: "056" },
    { code: "BZ", code3: "BLZ", name: "Belize", number: "084" },
    { code: "BJ", code3: "BEN", name: "Benin", number: "204" },
    { code: "BM", code3: "BMU", name: "Bermuda", number: "060" },
    { code: "BT", code3: "BTN", name: "Bhutan", number: "064" },
    {
        code: "BO",
        code3: "BOL",
        name: "Bolivia (Plurinational State of)",
        number: "068",
    },
    {
        code: "BQ",
        code3: "BES",
        name: "Bonaire, Sint Eustatius and Saba",
        number: "535",
    },
    { code: "BA", code3: "BIH", name: "Bosnia and Herzegovina", number: "070" },
    { code: "BW", code3: "BWA", name: "Botswana", number: "072" },
    { code: "BV", code3: "BVT", name: "Bouvet Island", number: "074" },
    { code: "BR", code3: "BRA", name: "Brazil", number: "076" },
    {
        code: "IO",
        code3: "IOT",
        name: "British Indian Ocean Territory (the)",
        number: "086",
    },
    { code: "BN", code3: "BRN", name: "Brunei Darussalam", number: "096" },
    { code: "BG", code3: "BGR", name: "Bulgaria", number: "100" },
    { code: "BF", code3: "BFA", name: "Burkina Faso", number: "854" },
    { code: "BI", code3: "BDI", name: "Burundi", number: "108" },
    { code: "CV", code3: "CPV", name: "Cabo Verde", number: "132" },
    { code: "KH", code3: "KHM", name: "Cambodia", number: "116" },
    { code: "CM", code3: "CMR", name: "Cameroon", number: "120" },
    { code: "CA", code3: "CAN", name: "Canada", number: "124" },
    { code: "KY", code3: "CYM", name: "Cayman Islands (the)", number: "136" },
    {
        code: "CF",
        code3: "CAF",
        name: "Central African Republic (the)",
        number: "140",
    },
    { code: "TD", code3: "TCD", name: "Chad", number: "148" },
    { code: "CL", code3: "CHL", name: "Chile", number: "152" },
    { code: "CN", code3: "CHN", name: "China", number: "156" },
    { code: "CX", code3: "CXR", name: "Christmas Island", number: "162" },
    {
        code: "CC",
        code3: "CCK",
        name: "Cocos (Keeling) Islands (the)",
        number: "166",
    },
    { code: "CO", code3: "COL", name: "Colombia", number: "170" },
    { code: "KM", code3: "COM", name: "Comoros (the)", number: "174" },
    {
        code: "CD",
        code3: "COD",
        name: "Congo (the Democratic Republic of the)",
        number: "180",
    },
    { code: "CG", code3: "COG", name: "Congo (the)", number: "178" },
    { code: "CK", code3: "COK", name: "Cook Islands (the)", number: "184" },
    { code: "CR", code3: "CRI", name: "Costa Rica", number: "188" },
    { code: "HR", code3: "HRV", name: "Croatia", number: "191" },
    { code: "CU", code3: "CUB", name: "Cuba", number: "192" },
    { code: "CW", code3: "CUW", name: "Curaçao", number: "531" },
    { code: "CY", code3: "CYP", name: "Cyprus", number: "196" },
    { code: "CZ", code3: "CZE", name: "Czechia", number: "203" },
    { code: "CI", code3: "CIV", name: "Côte d'Ivoire", number: "384" },
    { code: "DK", code3: "DNK", name: "Denmark", number: "208" },
    { code: "DJ", code3: "DJI", name: "Djibouti", number: "262" },
    { code: "DM", code3: "DMA", name: "Dominica", number: "212" },
    {
        code: "DO",
        code3: "DOM",
        name: "Dominican Republic (the)",
        number: "214",
    },
    { code: "EC", code3: "ECU", name: "Ecuador", number: "218" },
    { code: "EG", code3: "EGY", name: "Egypt", number: "818" },
    { code: "SV", code3: "SLV", name: "El Salvador", number: "222" },
    { code: "GQ", code3: "GNQ", name: "Equatorial Guinea", number: "226" },
    { code: "ER", code3: "ERI", name: "Eritrea", number: "232" },
    { code: "EE", code3: "EST", name: "Estonia", number: "233" },
    { code: "SZ", code3: "SWZ", name: "Eswatini", number: "748" },
    { code: "ET", code3: "ETH", name: "Ethiopia", number: "231" },
    {
        code: "FK",
        code3: "FLK",
        name: "Falkland Islands (the) [Malvinas]",
        number: "238",
    },
    { code: "FO", code3: "FRO", name: "Faroe Islands (the)", number: "234" },
    { code: "FJ", code3: "FJI", name: "Fiji", number: "242" },
    { code: "FI", code3: "FIN", name: "Finland", number: "246" },
    { code: "FR", code3: "FRA", name: "France", number: "250" },
    { code: "GF", code3: "GUF", name: "French Guiana", number: "254" },
    { code: "PF", code3: "PYF", name: "French Polynesia", number: "258" },
    {
        code: "TF",
        code3: "ATF",
        name: "French Southern Territories (the)",
        number: "260",
    },
    { code: "GA", code3: "GAB", name: "Gabon", number: "266" },
    { code: "GM", code3: "GMB", name: "Gambia (the)", number: "270" },
    { code: "GE", code3: "GEO", name: "Georgia", number: "268" },
    { code: "DE", code3: "DEU", name: "Germany", number: "276" },
    { code: "GH", code3: "GHA", name: "Ghana", number: "288" },
    { code: "GI", code3: "GIB", name: "Gibraltar", number: "292" },
    { code: "GR", code3: "GRC", name: "Greece", number: "300" },
    { code: "GL", code3: "GRL", name: "Greenland", number: "304" },
    { code: "GD", code3: "GRD", name: "Grenada", number: "308" },
    { code: "GP", code3: "GLP", name: "Guadeloupe", number: "312" },
    { code: "GU", code3: "GUM", name: "Guam", number: "316" },
    { code: "GT", code3: "GTM", name: "Guatemala", number: "320" },
    { code: "GG", code3: "GGY", name: "Guernsey", number: "831" },
    { code: "GN", code3: "GIN", name: "Guinea", number: "324" },
    { code: "GW", code3: "GNB", name: "Guinea-Bissau", number: "624" },
    { code: "GY", code3: "GUY", name: "Guyana", number: "328" },
    { code: "HT", code3: "HTI", name: "Haiti", number: "332" },
    {
        code: "HM",
        code3: "HMD",
        name: "Heard Island and McDonald Islands",
        number: "334",
    },
    { code: "VA", code3: "VAT", name: "Holy See (the)", number: "336" },
    { code: "HN", code3: "HND", name: "Honduras", number: "340" },
    { code: "HK", code3: "HKG", name: "Hong Kong", number: "344" },
    { code: "HU", code3: "HUN", name: "Hungary", number: "348" },
    { code: "IS", code3: "ISL", name: "Iceland", number: "352" },
    { code: "IN", code3: "IND", name: "India", number: "356" },
    { code: "ID", code3: "IDN", name: "Indonesia", number: "360" },
    {
        code: "IR",
        code3: "IRN",
        name: "Iran (Islamic Republic of)",
        number: "364",
    },
    { code: "IQ", code3: "IRQ", name: "Iraq", number: "368" },
    { code: "IE", code3: "IRL", name: "Ireland", number: "372" },
    { code: "IM", code3: "IMN", name: "Isle of Man", number: "833" },
    { code: "IL", code3: "ISR", name: "Israel", number: "376" },
    { code: "IT", code3: "ITA", name: "Italy", number: "380" },
    { code: "JM", code3: "JAM", name: "Jamaica", number: "388" },
    { code: "JP", code3: "JPN", name: "Japan", number: "392" },
    { code: "JE", code3: "JEY", name: "Jersey", number: "832" },
    { code: "JO", code3: "JOR", name: "Jordan", number: "400" },
    { code: "KZ", code3: "KAZ", name: "Kazakhstan", number: "398" },
    { code: "KE", code3: "KEN", name: "Kenya", number: "404" },
    { code: "KI", code3: "KIR", name: "Kiribati", number: "296" },
    {
        code: "KP",
        code3: "PRK",
        name: "Korea (the Democratic People's Republic of)",
        number: "408",
    },
    {
        code: "KR",
        code3: "KOR",
        name: "Korea (the Republic of)",
        number: "410",
    },
    { code: "KW", code3: "KWT", name: "Kuwait", number: "414" },
    { code: "KG", code3: "KGZ", name: "Kyrgyzstan", number: "417" },
    {
        code: "LA",
        code3: "LAO",
        name: "Lao People's Democratic Republic (the)",
        number: "418",
    },
    { code: "LV", code3: "LVA", name: "Latvia", number: "428" },
    { code: "LB", code3: "LBN", name: "Lebanon", number: "422" },
    { code: "LS", code3: "LSO", name: "Lesotho", number: "426" },
    { code: "LR", code3: "LBR", name: "Liberia", number: "430" },
    { code: "LY", code3: "LBY", name: "Libya", number: "434" },
    { code: "LI", code3: "LIE", name: "Liechtenstein", number: "438" },
    { code: "LT", code3: "LTU", name: "Lithuania", number: "440" },
    { code: "LU", code3: "LUX", name: "Luxembourg", number: "442" },
    { code: "MO", code3: "MAC", name: "Macao", number: "446" },
    { code: "MG", code3: "MDG", name: "Madagascar", number: "450" },
    { code: "MW", code3: "MWI", name: "Malawi", number: "454" },
    { code: "MY", code3: "MYS", name: "Malaysia", number: "458" },
    { code: "MV", code3: "MDV", name: "Maldives", number: "462" },
    { code: "ML", code3: "MLI", name: "Mali", number: "466" },
    { code: "MT", code3: "MLT", name: "Malta", number: "470" },
    { code: "MH", code3: "MHL", name: "Marshall Islands (the)", number: "584" },
    { code: "MQ", code3: "MTQ", name: "Martinique", number: "474" },
    { code: "MR", code3: "MRT", name: "Mauritania", number: "478" },
    { code: "MU", code3: "MUS", name: "Mauritius", number: "480" },
    { code: "YT", code3: "MYT", name: "Mayotte", number: "175" },
    { code: "MX", code3: "MEX", name: "Mexico", number: "484" },
    {
        code: "FM",
        code3: "FSM",
        name: "Micronesia (Federated States of)",
        number: "583",
    },
    {
        code: "MD",
        code3: "MDA",
        name: "Moldova (the Republic of)",
        number: "498",
    },
    { code: "MC", code3: "MCO", name: "Monaco", number: "492" },
    { code: "MN", code3: "MNG", name: "Mongolia", number: "496" },
    { code: "ME", code3: "MNE", name: "Montenegro", number: "499" },
    { code: "MS", code3: "MSR", name: "Montserrat", number: "500" },
    { code: "MA", code3: "MAR", name: "Morocco", number: "504" },
    { code: "MZ", code3: "MOZ", name: "Mozambique", number: "508" },
    { code: "MM", code3: "MMR", name: "Myanmar", number: "104" },
    { code: "NA", code3: "NAM", name: "Namibia", number: "516" },
    { code: "NR", code3: "NRU", name: "Nauru", number: "520" },
    { code: "NP", code3: "NPL", name: "Nepal", number: "524" },
    { code: "NL", code3: "NLD", name: "Netherlands (the)", number: "528" },
    { code: "NC", code3: "NCL", name: "New Caledonia", number: "540" },
    { code: "NZ", code3: "NZL", name: "New Zealand", number: "554" },
    { code: "NI", code3: "NIC", name: "Nicaragua", number: "558" },
    { code: "NE", code3: "NER", name: "Niger (the)", number: "562" },
    { code: "NG", code3: "NGA", name: "Nigeria", number: "566" },
    { code: "NU", code3: "NIU", name: "Niue", number: "570" },
    { code: "NF", code3: "NFK", name: "Norfolk Island", number: "574" },
    {
        code: "MP",
        code3: "MNP",
        name: "Northern Mariana Islands (the)",
        number: "580",
    },
    { code: "NO", code3: "NOR", name: "Norway", number: "578" },
    { code: "OM", code3: "OMN", name: "Oman", number: "512" },
    { code: "PK", code3: "PAK", name: "Pakistan", number: "586" },
    { code: "PW", code3: "PLW", name: "Palau", number: "585" },
    { code: "PS", code3: "PSE", name: "Palestine, State of", number: "275" },
    { code: "PA", code3: "PAN", name: "Panama", number: "591" },
    { code: "PG", code3: "PNG", name: "Papua New Guinea", number: "598" },
    { code: "PY", code3: "PRY", name: "Paraguay", number: "600" },
    { code: "PE", code3: "PER", name: "Peru", number: "604" },
    { code: "PH", code3: "PHL", name: "Philippines (the)", number: "608" },
    { code: "PN", code3: "PCN", name: "Pitcairn", number: "612" },
    { code: "PL", code3: "POL", name: "Poland", number: "616" },
    { code: "PT", code3: "PRT", name: "Portugal", number: "620" },
    { code: "PR", code3: "PRI", name: "Puerto Rico", number: "630" },
    { code: "QA", code3: "QAT", name: "Qatar", number: "634" },
    {
        code: "MK",
        code3: "MKD",
        name: "Republic of North Macedonia",
        number: "807",
    },
    { code: "RO", code3: "ROU", name: "Romania", number: "642" },
    {
        code: "RU",
        code3: "RUS",
        name: "Russian Federation (the)",
        number: "643",
    },
    { code: "RW", code3: "RWA", name: "Rwanda", number: "646" },
    { code: "RE", code3: "REU", name: "Réunion", number: "638" },
    { code: "BL", code3: "BLM", name: "Saint Barthélemy", number: "652" },
    {
        code: "SH",
        code3: "SHN",
        name: "Saint Helena, Ascension and Tristan da Cunha",
        number: "654",
    },
    { code: "KN", code3: "KNA", name: "Saint Kitts and Nevis", number: "659" },
    { code: "LC", code3: "LCA", name: "Saint Lucia", number: "662" },
    {
        code: "MF",
        code3: "MAF",
        name: "Saint Martin (French part)",
        number: "663",
    },
    {
        code: "PM",
        code3: "SPM",
        name: "Saint Pierre and Miquelon",
        number: "666",
    },
    {
        code: "VC",
        code3: "VCT",
        name: "Saint Vincent and the Grenadines",
        number: "670",
    },
    { code: "WS", code3: "WSM", name: "Samoa", number: "882" },
    { code: "SM", code3: "SMR", name: "San Marino", number: "674" },
    { code: "ST", code3: "STP", name: "Sao Tome and Principe", number: "678" },
    { code: "SA", code3: "SAU", name: "Saudi Arabia", number: "682" },
    { code: "SN", code3: "SEN", name: "Senegal", number: "686" },
    { code: "RS", code3: "SRB", name: "Serbia", number: "688" },
    { code: "SC", code3: "SYC", name: "Seychelles", number: "690" },
    { code: "SL", code3: "SLE", name: "Sierra Leone", number: "694" },
    { code: "SG", code3: "SGP", name: "Singapore", number: "702" },
    {
        code: "SX",
        code3: "SXM",
        name: "Sint Maarten (Dutch part)",
        number: "534",
    },
    { code: "SK", code3: "SVK", name: "Slovakia", number: "703" },
    { code: "SI", code3: "SVN", name: "Slovenia", number: "705" },
    { code: "SB", code3: "SLB", name: "Solomon Islands", number: "090" },
    { code: "SO", code3: "SOM", name: "Somalia", number: "706" },
    { code: "ZA", code3: "ZAF", name: "South Africa", number: "710" },
    {
        code: "GS",
        code3: "SGS",
        name: "South Georgia and the South Sandwich Islands",
        number: "239",
    },
    { code: "SS", code3: "SSD", name: "South Sudan", number: "728" },
    { code: "ES", code3: "ESP", name: "Spain", number: "724" },
    { code: "LK", code3: "LKA", name: "Sri Lanka", number: "144" },
    { code: "SD", code3: "SDN", name: "Sudan (the)", number: "729" },
    { code: "SR", code3: "SUR", name: "Suriname", number: "740" },
    { code: "SJ", code3: "SJM", name: "Svalbard and Jan Mayen", number: "744" },
    { code: "SE", code3: "SWE", name: "Sweden", number: "752" },
    { code: "CH", code3: "CHE", name: "Switzerland", number: "756" },
    { code: "SY", code3: "SYR", name: "Syrian Arab Republic", number: "760" },
    { code: "TW", code3: "TWN", name: "Taiwan", number: "158" },
    { code: "TJ", code3: "TJK", name: "Tajikistan", number: "762" },
    {
        code: "TZ",
        code3: "TZA",
        name: "Tanzania, United Republic of",
        number: "834",
    },
    { code: "TH", code3: "THA", name: "Thailand", number: "764" },
    { code: "TL", code3: "TLS", name: "Timor-Leste", number: "626" },
    { code: "TG", code3: "TGO", name: "Togo", number: "768" },
    { code: "TK", code3: "TKL", name: "Tokelau", number: "772" },
    { code: "TO", code3: "TON", name: "Tonga", number: "776" },
    { code: "TT", code3: "TTO", name: "Trinidad and Tobago", number: "780" },
    { code: "TN", code3: "TUN", name: "Tunisia", number: "788" },
    { code: "TR", code3: "TUR", name: "Turkey", number: "792" },
    { code: "TM", code3: "TKM", name: "Turkmenistan", number: "795" },
    {
        code: "TC",
        code3: "TCA",
        name: "Turks and Caicos Islands (the)",
        number: "796",
    },
    { code: "TV", code3: "TUV", name: "Tuvalu", number: "798" },
    { code: "UG", code3: "UGA", name: "Uganda", number: "800" },
    { code: "UA", code3: "UKR", name: "Ukraine", number: "804" },
    {
        code: "AE",
        code3: "ARE",
        name: "United Arab Emirates (the)",
        number: "784",
    },
    {
        code: "GB",
        code3: "GBR",
        name: "United Kingdom of Great Britain and Northern Ireland (the)",
        number: "826",
    },
    {
        code: "UM",
        code3: "UMI",
        name: "United States Minor Outlying Islands (the)",
        number: "581",
    },
    {
        code: "US",
        code3: "USA",
        name: "United States of America (the)",
        number: "840",
    },
    { code: "UY", code3: "URY", name: "Uruguay", number: "858" },
    { code: "UZ", code3: "UZB", name: "Uzbekistan", number: "860" },
    { code: "VU", code3: "VUT", name: "Vanuatu", number: "548" },
    {
        code: "VE",
        code3: "VEN",
        name: "Venezuela (Bolivarian Republic of)",
        number: "862",
    },
    { code: "VN", code3: "VNM", name: "Viet Nam", number: "704" },
    {
        code: "VG",
        code3: "VGB",
        name: "Virgin Islands (British)",
        number: "092",
    },
    { code: "VI", code3: "VIR", name: "Virgin Islands (U.S.)", number: "850" },
    { code: "WF", code3: "WLF", name: "Wallis and Futuna", number: "876" },
    { code: "EH", code3: "ESH", name: "Western Sahara", number: "732" },
    { code: "YE", code3: "YEM", name: "Yemen", number: "887" },
    { code: "ZM", code3: "ZMB", name: "Zambia", number: "894" },
    { code: "ZW", code3: "ZWE", name: "Zimbabwe", number: "716" },
    { code: "AX", code3: "ALA", name: "Åland Islands", number: "248" },
];

const job_name = [
    { id: 0, name: "Novice" },
    { id: 1, name: "Swordman" },
    { id: 2, name: "Magician" },
    { id: 3, name: "Archer" },
    { id: 4, name: "Acolyte" },
    { id: 5, name: "Merchant" },
    { id: 6, name: "Thief" },
    { id: 7, name: "Knight" },
    { id: 8, name: "Priest" },
    { id: 9, name: "Wizard" },
    { id: 10, name: "Blacksmith" },
    { id: 11, name: "Hunter" },
    { id: 12, name: "Assassin" },
    { id: 13, name: "Knight" },
    { id: 15, name: "Monk" },
    { id: 16, name: "Sage" },
    { id: 17, name: "Rogue" },
    { id: 18, name: "Alchemist" },
    { id: 19, name: "Bard" },
    { id: 20, name: "Dancer" },
    { id: 21, name: "Crusader" },
    { id: 22, name: "Super Novice" },
    { id: 24, name: "Gunslinger" },
    { id: 25, name: "Ninja" },
    { id: 26, name: "Santa Costume" },
    { id: 4001, name: "Novice High" },
    { id: 4002, name: "Swordsman High" },
    { id: 4003, name: "Mage High" },
    { id: 4004, name: "Archer High" },
    { id: 4005, name: "Acolyte High" },
    { id: 4006, name: "Merchant High" },
    { id: 4007, name: "Thief High" },
    { id: 4008, name: "Lord Knight" },
    { id: 4009, name: "High Priest" },
    { id: 4010, name: "High Wizard" },
    { id: 4011, name: "Whitesmith" },
    { id: 4012, name: "Sniper" },
    { id: 4013, name: "Assassin Cross" },
    { id: 4014, name: "Lord Knight" },
    { id: 4015, name: "Paladin" },
    { id: 4016, name: "Champion" },
    { id: 4017, name: "Professor" },
    { id: 4018, name: "Stalker" },
    { id: 4019, name: "Creator" },
    { id: 4020, name: "Clown" },
    { id: 4021, name: "Gypsy" },
    { id: 4022, name: "Paladin" },
    { id: 4023, name: "Baby Novice" },
    { id: 4024, name: "Baby Swordman" },
    { id: 4025, name: "Baby Magician" },
    { id: 4026, name: "Baby Archer" },
    { id: 4027, name: "Baby Acolyte" },
    { id: 4028, name: "Baby Merchant" },
    { id: 4029, name: "Baby Thief" },
    { id: 4030, name: "Baby Knight" },
    { id: 4031, name: "Baby Priest" },
    { id: 4032, name: "Baby Wizard" },
    { id: 4033, name: "Baby Blacksmith" },
    { id: 4034, name: "Baby Hunter" },
    { id: 4035, name: "Baby Assassin" },
    { id: 4036, name: "Baby Knight" },
    { id: 4037, name: "Baby Crusader" },
    { id: 4038, name: "Baby Monk" },
    { id: 4039, name: "Baby Sage" },
    { id: 4040, name: "Baby Rogue" },
    { id: 4041, name: "Baby Alchemist" },
    { id: 4042, name: "Baby Bard" },
    { id: 4043, name: "Baby Dancer" },
    { id: 4044, name: "Baby Crusader" },
    { id: 4045, name: "Baby Super Novice" },
    { id: 4046, name: "Taekwon" },
    { id: 4047, name: "Star Gladiator" },
    { id: 4048, name: "Star Gladiator" },
    { id: 4049, name: "Soul Linker" },
];

const ranking_jobs = [
    { id: 0, name: "Super Novice" },
    { id: 4009, name: "High Priest" },
    { id: 4010, name: "High Wizard" },
    { id: 4011, name: "Whitesmith" },
    { id: 4012, name: "Sniper" },
    { id: 4013, name: "Assassin Cross" },
    { id: 4014, name: "Lord Knight" },
    { id: 4015, name: "Paladin" },
    { id: 4016, name: "Champion" },
    { id: 4017, name: "Professor" },
    { id: 4018, name: "Stalker" },
    { id: 4019, name: "Creator" },
    { id: 4020, name: "Clown" },
    { id: 4021, name: "Gypsy" },
    // { id: 4022, name: "Paladin" },
    { id: 4046, name: "Taekwon" },
    { id: 4047, name: "Star Gladiator" },
    { id: 4049, name: "Soul Linker" },
    { id: 24, name: "Gunslinger" },
    { id: 25, name: "Ninja" },
];

const rank_woe_type = [
    { id: 1, name: "Ancient Woe" },
    { id: 2, name: "WoE 1.0" },
    { id: 3, name: "King of Emperium" },
    { id: 4, name: "WoE 2.0" },
    { id: 5, name: "Guilds War" },
];

const whosell_type = [
    {
        name_US: "All",
        name_ES: "Todos",
        name_FR: "Tous",
        name_PT: "Todos",
        id: "All",
    },
    {
        name_US: "Costumes",
        name_ES: "Trajes",
        name_FR: "Costumes",
        name_PT: "Trajes",
        id: "COSTUME",
    },
    {
        name_US: "Consumables",
        name_ES: "Consumibles",
        name_FR: "Consommables",
        name_PT: "Consumíveis",
        id: "CONS",
    },
    {
        name_US: "Weapons",
        name_ES: "Armas",
        name_FR: "Armes",
        name_PT: "Armas",

        id: "WEAP",
    },
    {
        name_US: "Armors",
        name_ES: "Armaduras",
        name_FR: "Armures",
        name_PT: "Armaduras",
        id: "ARMOR",
    },
    {
        name_US: "Cards",
        name_ES: "Cartas",
        name_FR: "Cartes",
        name_PT: "Cartas",
        id: "CARD",
    },
    {
        name_US: "Pet",
        name_ES: "Mascotas",
        name_FR: "Animaux",
        name_PT: "Animais",
        id: "PET",
    },
    {
        name_US: "ETC",
        name_ES: "ETC",
        name_FR: "ETC",
        name_PT: "ETC",
        id: "ETC",
    },
];

const itemdb_type = [
    {
        name_US: "All",
        name_ES: "Todos",
        name_FR: "Tous",
        name_PT: "Todos",
        id: "All",
    },
    {
        name_US: "Costumes",
        name_ES: "Trajes",
        name_FR: "Costumes",
        name_PT: "Trajes",
        id: "COSTUME",
    },
    {
        name_US: "Consumables",
        name_ES: "Consumibles",
        name_FR: "Consommables",
        name_PT: "Consumíveis",
        id: "CONS",
    },
    {
        name_US: "Weapons",
        name_ES: "Armas",
        name_FR: "Armes",
        name_PT: "Armas",

        id: "WEAP",
    },
    {
        name_US: "Armors",
        name_ES: "Armaduras",
        name_FR: "Armures",
        name_PT: "Armaduras",
        id: "ARMOR",
    },
    {
        name_US: "Cards",
        name_ES: "Cartas",
        name_FR: "Cartes",
        name_PT: "Cartas",
        id: "CARD",
    },
    {
        name_US: "Pet",
        name_ES: "Mascotas",
        name_FR: "Animaux",
        name_PT: "Animais",
        id: "PET",
    },
    {
        name_US: "ETC",
        name_ES: "ETC",
        name_FR: "ETC",
        name_PT: "ETC",
        id: "ETC",
    },
];

const skill_name = [
    {
        id: 1,
        name: "Basic Skill",
    },
    {
        id: 2,
        name: "Sword Mastery",
    },
    {
        id: 3,
        name: "Two-Handed Sword Mastery ",
    },
    {
        id: 4,
        name: "Increase HP Recovery",
    },
    {
        id: 5,
        name: "Bash",
    },
    {
        id: 6,
        name: "Provoke",
    },
    {
        id: 7,
        name: "Magnum Break ",
    },
    {
        id: 8,
        name: "Endure",
    },
    {
        id: 9,
        name: "Increase SP Recovery",
    },
    {
        id: 10,
        name: "Sight",
    },
    {
        id: 11,
        name: "Napalm Beat",
    },
    {
        id: 12,
        name: "Safety Wall",
    },
    {
        id: 13,
        name: "Soul Strike",
    },
    {
        id: 14,
        name: "Cold Bolt  ",
    },
    {
        id: 15,
        name: "Frost Diver",
    },
    {
        id: 16,
        name: "Stone Curse",
    },
    {
        id: 17,
        name: "Fire Ball  ",
    },
    {
        id: 18,
        name: "Fire Wall  ",
    },
    {
        id: 19,
        name: "Fire Bolt  ",
    },
    {
        id: 20,
        name: "Lightning Bolt  ",
    },
    {
        id: 21,
        name: "Thunderstorm ",
    },
    {
        id: 22,
        name: "Divine Protection",
    },
    {
        id: 23,
        name: "Demon Bane ",
    },
    {
        id: 24,
        name: "Ruwach",
    },
    {
        id: 25,
        name: "Pneuma",
    },
    {
        id: 26,
        name: "Teleport",
    },
    {
        id: 27,
        name: "Warp Portal",
    },
    {
        id: 28,
        name: "Heal",
    },
    {
        id: 29,
        name: "Increase AGI ",
    },
    {
        id: 30,
        name: "Decrease AGI ",
    },
    {
        id: 31,
        name: "Aqua Benedicta  ",
    },
    {
        id: 32,
        name: "Signum Crucis",
    },
    {
        id: 33,
        name: "Angelus",
    },
    {
        id: 34,
        name: "Blessing",
    },
    {
        id: 35,
        name: "Cure",
    },
    {
        id: 36,
        name: "Enlarge Weight Limit",
    },
    {
        id: 37,
        name: "Discount",
    },
    {
        id: 38,
        name: "Overcharge ",
    },
    {
        id: 39,
        name: "Pushcart",
    },
    {
        id: 40,
        name: "Item Appraisal  ",
    },
    {
        id: 41,
        name: "Vending",
    },
    {
        id: 42,
        name: "Mammonite  ",
    },
    {
        id: 43,
        name: "Owl's Eye  ",
    },
    {
        id: 44,
        name: "Vulture's Eye",
    },
    {
        id: 45,
        name: "Improve Concentration  ",
    },
    {
        id: 46,
        name: "Double Strafe",
    },
    {
        id: 47,
        name: "Arrow Shower ",
    },
    {
        id: 48,
        name: "Double Attack",
    },
    {
        id: 49,
        name: "Improve Dodge",
    },
    {
        id: 50,
        name: "Steal",
    },
    {
        id: 51,
        name: "Hiding",
    },
    {
        id: 52,
        name: "Envenom",
    },
    {
        id: 53,
        name: "Detoxify",
    },
    {
        id: 54,
        name: "Resurrection ",
    },
    {
        id: 55,
        name: "Spear Mastery",
    },
    {
        id: 56,
        name: "Pierce",
    },
    {
        id: 57,
        name: "Brandish Spear  ",
    },
    {
        id: 58,
        name: "Spear Stab ",
    },
    {
        id: 59,
        name: "Spear Boomerang ",
    },
    {
        id: 60,
        name: "Twohand Quicken ",
    },
    {
        id: 61,
        name: "Counter Attack  ",
    },
    {
        id: 62,
        name: "Bowling Bash ",
    },
    {
        id: 63,
        name: "Peco Peco Riding",
    },
    {
        id: 64,
        name: "Cavalier Mastery",
    },
    {
        id: 65,
        name: "Mace Mastery ",
    },
    {
        id: 66,
        name: "Impositio Manus ",
    },
    {
        id: 67,
        name: "Suffragium ",
    },
    {
        id: 68,
        name: "Aspersio",
    },
    {
        id: 69,
        name: "B.S. Sacramenti ",
    },
    {
        id: 70,
        name: "Sanctuary  ",
    },
    {
        id: 71,
        name: "Slow Poison",
    },
    {
        id: 72,
        name: "Status Recovery ",
    },
    {
        id: 73,
        name: "Kyrie Eleison",
    },
    {
        id: 74,
        name: "Magnificat ",
    },
    {
        id: 75,
        name: "Gloria",
    },
    {
        id: 76,
        name: "Lex Divina ",
    },
    {
        id: 77,
        name: "Turn Undead",
    },
    {
        id: 78,
        name: "Lex Aeterna",
    },
    {
        id: 79,
        name: "Magnus Exorcismus",
    },
    {
        id: 80,
        name: "Fire Pillar",
    },
    {
        id: 81,
        name: "Sightrasher",
    },
    {
        id: 82,
        name: "Fire Ivy",
    },
    {
        id: 83,
        name: "Meteor Storm ",
    },
    {
        id: 84,
        name: "Jupitel Thunder ",
    },
    {
        id: 85,
        name: "Lord of Vermilion",
    },
    {
        id: 86,
        name: "Water Ball ",
    },
    {
        id: 87,
        name: "Ice Wall",
    },
    {
        id: 88,
        name: "Frost Nova ",
    },
    {
        id: 89,
        name: "Storm Gust ",
    },
    {
        id: 90,
        name: "Earth Spike",
    },
    {
        id: 91,
        name: "Heaven's Drive  ",
    },
    {
        id: 92,
        name: "Quagmire",
    },
    {
        id: 93,
        name: "Sense",
    },
    {
        id: 94,
        name: "Iron Tempering  ",
    },
    {
        id: 95,
        name: "Steel Tempering ",
    },
    {
        id: 96,
        name: "Enchanted Stone Craft  ",
    },
    {
        id: 97,
        name: "Oridecon Research",
    },
    {
        id: 98,
        name: "Smith Dagger ",
    },
    {
        id: 99,
        name: "Smith Sword",
    },
    {
        id: 100,
        name: "Smith Two-handed Sword ",
    },
    {
        id: 101,
        name: "Smith Axe  ",
    },
    {
        id: 102,
        name: "Smith Mace ",
    },
    {
        id: 103,
        name: "Smith Knucklebrace",
    },
    {
        id: 104,
        name: "Smith Spear",
    },
    {
        id: 105,
        name: "Hilt Binding ",
    },
    {
        id: 106,
        name: "Ore Discovery",
    },
    {
        id: 107,
        name: "Weaponry Research",
    },
    {
        id: 108,
        name: "Weapon Repair",
    },
    {
        id: 109,
        name: "Skin Tempering  ",
    },
    {
        id: 110,
        name: "Hammer Fall",
    },
    {
        id: 111,
        name: "Adrenaline Rush ",
    },
    {
        id: 112,
        name: "Weapon Perfection",
    },
    {
        id: 113,
        name: "Power-Thrust ",
    },
    {
        id: 114,
        name: "Maximize Power  ",
    },
    {
        id: 115,
        name: "Skid Trap  ",
    },
    {
        id: 116,
        name: "Land Mine  ",
    },
    {
        id: 117,
        name: "Ankle Snare",
    },
    {
        id: 118,
        name: "Shockwave Trap  ",
    },
    {
        id: 119,
        name: "Sandman",
    },
    {
        id: 120,
        name: "Flasher",
    },
    {
        id: 121,
        name: "Freezing Trap",
    },
    {
        id: 122,
        name: "Blast Mine ",
    },
    {
        id: 123,
        name: "Claymore Trap",
    },
    {
        id: 124,
        name: "Remove Trap",
    },
    {
        id: 125,
        name: "Talkie Box ",
    },
    {
        id: 126,
        name: "Beast Bane ",
    },
    {
        id: 127,
        name: "Falconry Mastery",
    },
    {
        id: 128,
        name: "Steel Crow ",
    },
    {
        id: 129,
        name: "Blitz Beat ",
    },
    {
        id: 130,
        name: "Detect",
    },
    {
        id: 131,
        name: "Spring Trap",
    },
    {
        id: 132,
        name: "Righthand Mastery",
    },
    {
        id: 133,
        name: "Lefthand Mastery",
    },
    {
        id: 134,
        name: "Katar Mastery",
    },
    {
        id: 135,
        name: "Cloaking",
    },
    {
        id: 136,
        name: "Sonic Blow ",
    },
    {
        id: 137,
        name: "Grimtooth  ",
    },
    {
        id: 138,
        name: "Enchant Poison  ",
    },
    {
        id: 139,
        name: "Poison React ",
    },
    {
        id: 140,
        name: "Venom Dust ",
    },
    {
        id: 141,
        name: "Venom Splasher  ",
    },
    {
        id: 142,
        name: "First Aid  ",
    },
    {
        id: 143,
        name: "Play Dead  ",
    },
    {
        id: 144,
        name: "Moving HP-Recovery",
    },
    {
        id: 145,
        name: "Fatal Blow ",
    },
    {
        id: 146,
        name: "Auto Berserk ",
    },
    {
        id: 147,
        name: "Arrow Crafting  ",
    },
    {
        id: 148,
        name: "Arrow Repel",
    },
    {
        id: 149,
        name: "Sand Attack",
    },
    {
        id: 150,
        name: "Back Slide ",
    },
    {
        id: 151,
        name: "Find Stone ",
    },
    {
        id: 152,
        name: "Stone Fling",
    },
    {
        id: 153,
        name: "Cart Revolution ",
    },
    {
        id: 154,
        name: "Change Cart",
    },
    {
        id: 155,
        name: "Crazy Uproar ",
    },
    {
        id: 156,
        name: "Holy Light ",
    },
    {
        id: 157,
        name: "Energy Coat",
    },
    {
        id: 158,
        name: "Piercing Attack ",
    },
    {
        id: 159,
        name: "Spirit Destruction",
    },
    {
        id: 160,
        name: "Stand off attack",
    },
    {
        id: 161,
        name: "Attribute Change",
    },
    {
        id: 162,
        name: "Water Attribute Change ",
    },
    {
        id: 163,
        name: "Earth Attribute Change ",
    },
    {
        id: 164,
        name: "Fire Attribute Change  ",
    },
    {
        id: 165,
        name: "Wind Attribute Change  ",
    },
    {
        id: 166,
        name: "Poison Attribute Change",
    },
    {
        id: 167,
        name: "Holy Attribute Change  ",
    },
    {
        id: 168,
        name: "Shadow Attribute Change",
    },
    {
        id: 169,
        name: "Ghost Attribute Change ",
    },
    {
        id: 170,
        name: "Defense disregard attack ",
    },
    {
        id: 171,
        name: "Multi-stage Attack",
    },
    {
        id: 172,
        name: "Guided Attack",
    },
    {
        id: 173,
        name: "Suicide bombing ",
    },
    {
        id: 174,
        name: "Splash attack",
    },
    {
        id: 175,
        name: "Suicide",
    },
    {
        id: 176,
        name: "Poison Attack",
    },
    {
        id: 177,
        name: "Blind Attack ",
    },
    {
        id: 178,
        name: "Silence Attack  ",
    },
    {
        id: 179,
        name: "Stun Attack",
    },
    {
        id: 180,
        name: "Petrify Attack  ",
    },
    {
        id: 181,
        name: "Curse Attack ",
    },
    {
        id: 182,
        name: "Sleep attack ",
    },
    {
        id: 183,
        name: "Random Attack",
    },
    {
        id: 184,
        name: "Water Attribute Attack ",
    },
    {
        id: 185,
        name: "Earth Attribute Attack ",
    },
    {
        id: 186,
        name: "Fire Attribute Attack  ",
    },
    {
        id: 187,
        name: "Wind Attribute Attack  ",
    },
    {
        id: 188,
        name: "Poison Attribute Attack",
    },
    {
        id: 189,
        name: "Holy Attribute Attack  ",
    },
    {
        id: 190,
        name: "Shadow Attribute Attack",
    },
    {
        id: 191,
        name: "Ghost Attribute Attack ",
    },
    {
        id: 192,
        name: "Demon Shock Attack",
    },
    {
        id: 193,
        name: "Metamorphosis",
    },
    {
        id: 194,
        name: "Provocation",
    },
    {
        id: 195,
        name: "Smoking",
    },
    {
        id: 196,
        name: "Follower Summons",
    },
    {
        id: 197,
        name: "Emotion",
    },
    {
        id: 198,
        name: "Transformation  ",
    },
    {
        id: 199,
        name: "Sucking Blood",
    },
    {
        id: 200,
        name: "Energy Drain ",
    },
    {
        id: 201,
        name: "Keeping",
    },
    {
        id: 202,
        name: "Dark Breath",
    },
    {
        id: 203,
        name: "Dark Blessing",
    },
    {
        id: 204,
        name: "Barrier",
    },
    {
        id: 205,
        name: "Defender",
    },
    {
        id: 206,
        name: "Lick",
    },
    {
        id: 207,
        name: "Hallucination",
    },
    {
        id: 208,
        name: "Rebirth",
    },
    {
        id: 209,
        name: "Monster Summons ",
    },
    {
        id: 210,
        name: "Gank",
    },
    {
        id: 211,
        name: "Mug ",
    },
    {
        id: 212,
        name: "Back Stab  ",
    },
    {
        id: 213,
        name: "Stalk",
    },
    {
        id: 214,
        name: "Sightless Mind  ",
    },
    {
        id: 215,
        name: "Divest Weapon",
    },
    {
        id: 216,
        name: "Divest Shield",
    },
    {
        id: 217,
        name: "Divest Armor ",
    },
    {
        id: 218,
        name: "Divest Helm",
    },
    {
        id: 219,
        name: "Snatch",
    },
    {
        id: 220,
        name: "Scribble",
    },
    {
        id: 221,
        name: "Piece",
    },
    {
        id: 222,
        name: "Remover",
    },
    {
        id: 223,
        name: "Slyness",
    },
    {
        id: 224,
        name: "Haggle",
    },
    {
        id: 225,
        name: "Intimidate ",
    },
    {
        id: 226,
        name: "Axe Mastery",
    },
    {
        id: 227,
        name: "Potion Research ",
    },
    {
        id: 228,
        name: "Prepare Potion  ",
    },
    {
        id: 229,
        name: "Bomb",
    },
    {
        id: 230,
        name: "Acid Terror",
    },
    {
        id: 231,
        name: "Aid Potion ",
    },
    {
        id: 232,
        name: "Summon Flora ",
    },
    {
        id: 233,
        name: "Summon Marine Sphere",
    },
    {
        id: 234,
        name: "Alchemical Weapon",
    },
    {
        id: 235,
        name: "Synthesized Shield",
    },
    {
        id: 236,
        name: "Synthetic Armor ",
    },
    {
        id: 237,
        name: "Biochemical Helm",
    },
    {
        id: 238,
        name: "Bioethics  ",
    },
    {
        id: 239,
        name: "Biotechnology",
    },
    {
        id: 240,
        name: "Life Creation",
    },
    {
        id: 241,
        name: "Cultivation",
    },
    {
        id: 242,
        name: "Flame Control",
    },
    {
        id: 243,
        name: "Call Homunculus ",
    },
    {
        id: 244,
        name: "Vaporize",
    },
    {
        id: 245,
        name: "Drillmaster",
    },
    {
        id: 246,
        name: "Heal Homunculus ",
    },
    {
        id: 247,
        name: "Homunculus Resurrection",
    },
    {
        id: 248,
        name: "Faith",
    },
    {
        id: 249,
        name: "Guard",
    },
    {
        id: 250,
        name: "Smite",
    },
    {
        id: 251,
        name: "Shield Boomerang",
    },
    {
        id: 252,
        name: "Shield Reflect  ",
    },
    {
        id: 253,
        name: "Holy Cross ",
    },
    {
        id: 254,
        name: "Grand Cross",
    },
    {
        id: 255,
        name: "Sacrifice  ",
    },
    {
        id: 256,
        name: "Resistant Souls ",
    },
    {
        id: 257,
        name: "Defending Aura  ",
    },
    {
        id: 258,
        name: "Spear Quicken",
    },
    {
        id: 259,
        name: "Iron Fists ",
    },
    {
        id: 260,
        name: "Spiritual Cadence",
    },
    {
        id: 261,
        name: "Summon Spirit Sphere",
    },
    {
        id: 262,
        name: "Absorb Spirit Sphere",
    },
    {
        id: 263,
        name: "Raging Trifecta Blow",
    },
    {
        id: 264,
        name: "Snap",
    },
    {
        id: 265,
        name: "Dodge",
    },
    {
        id: 266,
        name: "Occult Impaction",
    },
    {
        id: 267,
        name: "Throw Spirit Sphere",
    },
    {
        id: 268,
        name: "Mental Strength ",
    },
    {
        id: 269,
        name: "Root",
    },
    {
        id: 270,
        name: "Fury",
    },
    {
        id: 271,
        name: "Asura Strike ",
    },
    {
        id: 272,
        name: "Raging Quadruple Blow  ",
    },
    {
        id: 273,
        name: "Raging Thrust",
    },
    {
        id: 274,
        name: "Study",
    },
    {
        id: 275,
        name: "Cast Cancel",
    },
    {
        id: 276,
        name: "Magic Rod  ",
    },
    {
        id: 277,
        name: "Spell Breaker",
    },
    {
        id: 278,
        name: "Free Cast  ",
    },
    {
        id: 279,
        name: "Hindsight  ",
    },
    {
        id: 280,
        name: "Endow Blaze",
    },
    {
        id: 281,
        name: "Endow Tsunami",
    },
    {
        id: 282,
        name: "Endow Tornado",
    },
    {
        id: 283,
        name: "Endow Quake",
    },
    {
        id: 284,
        name: "Dragonology",
    },
    {
        id: 285,
        name: "Volcano",
    },
    {
        id: 286,
        name: "Deluge",
    },
    {
        id: 287,
        name: "Whirlwind  ",
    },
    {
        id: 288,
        name: "Magnetic Earth  ",
    },
    {
        id: 289,
        name: "Dispell",
    },
    {
        id: 290,
        name: "Hocus-pocus",
    },
    {
        id: 291,
        name: "Monocell",
    },
    {
        id: 292,
        name: "Class Change ",
    },
    {
        id: 293,
        name: "Monster Chant",
    },
    {
        id: 294,
        name: "Grampus Morph",
    },
    {
        id: 295,
        name: "Grim Reaper",
    },
    {
        id: 296,
        name: "Gold Digger",
    },
    {
        id: 297,
        name: "Beastly Hypnosis",
    },
    {
        id: 298,
        name: "Questioning",
    },
    {
        id: 299,
        name: "Gravity",
    },
    {
        id: 300,
        name: "Leveling",
    },
    {
        id: 301,
        name: "Suicide",
    },
    {
        id: 302,
        name: "Rejuvenation ",
    },
    {
        id: 303,
        name: "Coma",
    },
    {
        id: 304,
        name: "Amp ",
    },
    {
        id: 305,
        name: "Encore",
    },
    {
        id: 306,
        name: "Lullaby",
    },
    {
        id: 307,
        name: "Mental Sensing  ",
    },
    {
        id: 308,
        name: "Down Tempo ",
    },
    {
        id: 309,
        name: "Battle Theme ",
    },
    {
        id: 310,
        name: "Harmonic Lick",
    },
    {
        id: 311,
        name: "Classical Pluck ",
    },
    {
        id: 312,
        name: "Power Chord",
    },
    {
        id: 313,
        name: "Acoustic Rhythm ",
    },
    {
        id: 314,
        name: "Ragnarok",
    },
    {
        id: 315,
        name: "Music Lessons",
    },
    {
        id: 316,
        name: "Melody Strike",
    },
    {
        id: 317,
        name: "Unchained Serenade",
    },
    {
        id: 318,
        name: "Unbarring Octave",
    },
    {
        id: 319,
        name: "Perfect Tablature",
    },
    {
        id: 320,
        name: "Impressive Riff ",
    },
    {
        id: 321,
        name: "Magic Strings",
    },
    {
        id: 322,
        name: "Song of Lutie",
    },
    {
        id: 323,
        name: "Dance Lessons",
    },
    {
        id: 324,
        name: "Slinging Arrow  ",
    },
    {
        id: 325,
        name: "Hip Shaker ",
    },
    {
        id: 326,
        name: "Dazzler",
    },
    {
        id: 327,
        name: "Focus Ballet ",
    },
    {
        id: 328,
        name: "Slow Grace ",
    },
    {
        id: 329,
        name: "Lady Luck  ",
    },
    {
        id: 330,
        name: "Gypsy's Kiss ",
    },
    {
        id: 331,
        name: "Random Move",
    },
    {
        id: 332,
        name: "Speed UP",
    },
    {
        id: 333,
        name: "Revenge",
    },
    {
        id: 334,
        name: "I Will Protect You",
    },
    {
        id: 335,
        name: "I Look up to You",
    },
    {
        id: 336,
        name: "I miss You ",
    },
    {
        id: 337,
        name: "Throw Tomahawk  ",
    },
    {
        id: 338,
        name: "Cross of Darkness",
    },
    {
        id: 339,
        name: "Grand cross of Darkness",
    },
    {
        id: 340,
        name: "Soul Strike of Darkness",
    },
    {
        id: 341,
        name: "Darkness Jupitel",
    },
    {
        id: 342,
        name: "Stop",
    },
    {
        id: 343,
        name: "Break weapon ",
    },
    {
        id: 344,
        name: "Break armor",
    },
    {
        id: 345,
        name: "Break helm ",
    },
    {
        id: 346,
        name: "Break shield ",
    },
    {
        id: 347,
        name: "Undead Element Attack  ",
    },
    {
        id: 348,
        name: "Undead Attribute Change",
    },
    {
        id: 349,
        name: "Power Up",
    },
    {
        id: 350,
        name: "Agility UP ",
    },
    {
        id: 351,
        name: "Siege Mode ",
    },
    {
        id: 352,
        name: "Recall Slaves",
    },
    {
        id: 353,
        name: "Invisible  ",
    },
    {
        id: 354,
        name: "Run ",
    },
    {
        id: 355,
        name: "Aura Blade ",
    },
    {
        id: 356,
        name: "Parrying",
    },
    {
        id: 357,
        name: "Concentration",
    },
    {
        id: 358,
        name: "Relax",
    },
    {
        id: 359,
        name: "Frenzy",
    },
    {
        id: 360,
        name: "Fury",
    },
    {
        id: 361,
        name: "Assumptio  ",
    },
    {
        id: 362,
        name: "Basilica",
    },
    {
        id: 363,
        name: "Meditatio  ",
    },
    {
        id: 364,
        name: "Soul Drain ",
    },
    {
        id: 365,
        name: "Stave Crasher",
    },
    {
        id: 366,
        name: "Mystical Amplification ",
    },
    {
        id: 367,
        name: "Gloria Domini	",
    },
    {
        id: 368,
        name: "Martyr's Reckoning",
    },
    {
        id: 369,
        name: "Battle Chant ",
    },
    {
        id: 370,
        name: "Raging Palm Strike",
    },
    {
        id: 371,
        name: "Glacier Fist ",
    },
    {
        id: 372,
        name: "Chain Crush Combo",
    },
    {
        id: 373,
        name: "Indulge",
    },
    {
        id: 374,
        name: "Soul Exhale",
    },
    {
        id: 375,
        name: "Soul Siphon",
    },
    {
        id: 376,
        name: "Advanced Katar Mastery ",
    },
    {
        id: 377,
        name: "Hallucination Walk",
    },
    {
        id: 378,
        name: "Enchant Deadly Poison  ",
    },
    {
        id: 379,
        name: "Soul Destroyer  ",
    },
    {
        id: 380,
        name: "Falcon Eyes",
    },
    {
        id: 381,
        name: "Falcon Assault  ",
    },
    {
        id: 382,
        name: "Focused Arrow Strike",
    },
    {
        id: 383,
        name: "Wind Walker",
    },
    {
        id: 384,
        name: "Shattering Strike",
    },
    {
        id: 385,
        name: "Create Coins ",
    },
    {
        id: 386,
        name: "Create Nuggets  ",
    },
    {
        id: 387,
        name: "Cart Boost ",
    },
    {
        id: 388,
        name: "Auto Attack System",
    },
    {
        id: 389,
        name: "Stealth",
    },
    {
        id: 390,
        name: "Counter Instinct",
    },
    {
        id: 391,
        name: "Steal Backpack  ",
    },
    {
        id: 392,
        name: "Alchemy",
    },
    {
        id: 393,
        name: "Potion Synthesis",
    },
    {
        id: 394,
        name: "Vulcan Arrow ",
    },
    {
        id: 395,
        name: "Sheltering Bliss",
    },
    {
        id: 396,
        name: "Marionette Control",
    },
    {
        id: 397,
        name: "Spiral Pierce",
    },
    {
        id: 398,
        name: "Traumatic Blow  ",
    },
    {
        id: 399,
        name: "Vital Strike ",
    },
    {
        id: 400,
        name: "Napalm Vulcan",
    },
    {
        id: 401,
        name: "Zen ",
    },
    {
        id: 402,
        name: "Mind Breaker ",
    },
    {
        id: 403,
        name: "Foresight  ",
    },
    {
        id: 404,
        name: "Blinding Mist",
    },
    {
        id: 405,
        name: "Fiber Lock ",
    },
    {
        id: 406,
        name: "Meteor Assault  ",
    },
    {
        id: 407,
        name: "Create Deadly Poison",
    },
    {
        id: 408,
        name: "Baby",
    },
    {
        id: 409,
        name: "Call Parent",
    },
    {
        id: 410,
        name: "Call Baby  ",
    },
    {
        id: 411,
        name: "Running",
    },
    {
        id: 412,
        name: "Tornado Stance  ",
    },
    {
        id: 413,
        name: "Tornado Kick ",
    },
    {
        id: 414,
        name: "Heel Drop Stance",
    },
    {
        id: 415,
        name: "Heel Drop  ",
    },
    {
        id: 416,
        name: "Roundhouse Stance",
    },
    {
        id: 417,
        name: "Roundhouse Kick ",
    },
    {
        id: 418,
        name: "Counter Kick Stance",
    },
    {
        id: 419,
        name: "Counter Kick ",
    },
    {
        id: 420,
        name: "Tumbling",
    },
    {
        id: 421,
        name: "Flying Kick",
    },
    {
        id: 422,
        name: "Peaceful Break  ",
    },
    {
        id: 423,
        name: "Happy Break",
    },
    {
        id: 424,
        name: "Kihop",
    },
    {
        id: 425,
        name: "Mild Wind  ",
    },
    {
        id: 426,
        name: "Taekwon Jump ",
    },
    {
        id: 427,
        name: "Feeling the Sun Moon and Stars",
    },
    {
        id: 428,
        name: "Warmth of the Sun",
    },
    {
        id: 429,
        name: "Warmth of the Moon",
    },
    {
        id: 430,
        name: "Warmth of the Stars",
    },
    {
        id: 431,
        name: "Comfort of the Sun",
    },
    {
        id: 432,
        name: "Comfort of the Moon",
    },
    {
        id: 433,
        name: "Comfort of the Stars",
    },
    {
        id: 434,
        name: "Hatred of the Sun Moon and Stars",
    },
    {
        id: 435,
        name: "Anger of the Sun",
    },
    {
        id: 436,
        name: "Anger of the Moon",
    },
    {
        id: 437,
        name: "Anger of the Stars",
    },
    {
        id: 438,
        name: "Blessing of the Sun",
    },
    {
        id: 439,
        name: "Blessing of the Moon",
    },
    {
        id: 440,
        name: "Blessing of the Stars  ",
    },
    {
        id: 441,
        name: "Demon of the Sun Moon and Stars",
    },
    {
        id: 442,
        name: "Friend of the Sun Moon and Stars",
    },
    {
        id: 443,
        name: "Knowledge of the Sun Moon and Stars  ",
    },
    {
        id: 444,
        name: "Union of the Sun Moon and Stars",
    },
    {
        id: 445,
        name: "Spirit of the Alchemist",
    },
    {
        id: 446,
        name: "Aid Berserk Potion",
    },
    {
        id: 447,
        name: "Spirit of the Monk",
    },
    {
        id: 448,
        name: "Spirit of the Star Gladiator  ",
    },
    {
        id: 449,
        name: "Spirit of the Sage",
    },
    {
        id: 450,
        name: "Spirit of the Crusader ",
    },
    {
        id: 451,
        name: "Spirit of the Supernovice",
    },
    {
        id: 452,
        name: "Spirit of the Knight",
    },
    {
        id: 453,
        name: "Spirit of the Wizard",
    },
    {
        id: 454,
        name: "Spirit of the Priest",
    },
    {
        id: 455,
        name: "Spirit of the Artist",
    },
    {
        id: 456,
        name: "Spirit of the Rogue",
    },
    {
        id: 457,
        name: "Spirit of the Assasin  ",
    },
    {
        id: 458,
        name: "Spirit of the Blacksmith ",
    },
    {
        id: 459,
        name: "Advanced Adrenaline Rush ",
    },
    {
        id: 460,
        name: "Spirit of the Hunter",
    },
    {
        id: 461,
        name: "Spirit of the Soul Linker",
    },
    {
        id: 462,
        name: "Kaizel",
    },
    {
        id: 463,
        name: "Kaahi",
    },
    {
        id: 464,
        name: "Kaupe",
    },
    {
        id: 465,
        name: "Kaite",
    },
    {
        id: 466,
        name: "Kaina",
    },
    {
        id: 467,
        name: "Estin",
    },
    {
        id: 468,
        name: "Estun",
    },
    {
        id: 469,
        name: "Esma",
    },
    {
        id: 470,
        name: "Eswoo",
    },
    {
        id: 471,
        name: "Eske",
    },
    {
        id: 472,
        name: "Eska",
    },
    {
        id: 473,
        name: "Provoke Self ",
    },
    {
        id: 474,
        name: "Emotion ON ",
    },
    {
        id: 475,
        name: "Preserve",
    },
    {
        id: 476,
        name: "Divest All ",
    },
    {
        id: 477,
        name: "Upgrade Weapon  ",
    },
    {
        id: 478,
        name: "Aid Condensed Potion",
    },
    {
        id: 479,
        name: "Full Protection ",
    },
    {
        id: 480,
        name: "Shield Chain ",
    },
    {
        id: 481,
        name: "Mana Recharge",
    },
    {
        id: 482,
        name: "Double Casting  ",
    },
    {
        id: 483,
        name: "Ganbantein ",
    },
    {
        id: 484,
        name: "Gravitation Field",
    },
    {
        id: 485,
        name: "Cart Termination",
    },
    {
        id: 486,
        name: "Maximum Power Thrust",
    },
    {
        id: 487,
        name: "Longing for Freedom",
    },
    {
        id: 488,
        name: "Wand of Hermode ",
    },
    {
        id: 489,
        name: "Tarot Card of Fate",
    },
    {
        id: 490,
        name: "Acid Demonstration",
    },
    {
        id: 491,
        name: "Plant Cultivation",
    },
    {
        id: 492,
        name: "Weapon Enchantment",
    },
    {
        id: 493,
        name: "Taekwon Mission ",
    },
    {
        id: 494,
        name: "Spirit of Rebirth",
    },
    {
        id: 495,
        name: "Onehand Quicken ",
    },
    {
        id: 496,
        name: "Twilight Alchemy 1",
    },
    {
        id: 497,
        name: "Twilight Alchemy 2",
    },
    {
        id: 498,
        name: "Twilight Alchemy 3",
    },
    {
        id: 499,
        name: "Beast Strafing  ",
    },
    {
        id: 500,
        name: "Flip the Coin",
    },
    {
        id: 501,
        name: "Fling",
    },
    {
        id: 502,
        name: "Triple Action",
    },
    {
        id: 503,
        name: "Bulls Eye  ",
    },
    {
        id: 504,
        name: "Madness Canceller",
    },
    {
        id: 505,
        name: "AdJustment ",
    },
    {
        id: 506,
        name: "Increasing Accuracy",
    },
    {
        id: 507,
        name: "Magical Bullet  ",
    },
    {
        id: 508,
        name: "Cracker",
    },
    {
        id: 509,
        name: "Single Action",
    },
    {
        id: 510,
        name: "Snake Eye  ",
    },
    {
        id: 511,
        name: "Chain Action ",
    },
    {
        id: 512,
        name: "Tracking",
    },
    {
        id: 513,
        name: "Disarm",
    },
    {
        id: 514,
        name: "Piercing Shot",
    },
    {
        id: 515,
        name: "Rapid Shower ",
    },
    {
        id: 516,
        name: "Desperado  ",
    },
    {
        id: 517,
        name: "Gatling Fever",
    },
    {
        id: 518,
        name: "Dust",
    },
    {
        id: 519,
        name: "Full Buster",
    },
    {
        id: 520,
        name: "Spread Attack",
    },
    {
        id: 521,
        name: "Ground Drift ",
    },
    {
        id: 522,
        name: "Shuriken Training",
    },
    {
        id: 523,
        name: "Throw Shuriken  ",
    },
    {
        id: 524,
        name: "Throw Kunai",
    },
    {
        id: 525,
        name: "Throw Huuma Shuriken",
    },
    {
        id: 526,
        name: "Throw Zeny ",
    },
    {
        id: 527,
        name: "Improvised Defense",
    },
    {
        id: 528,
        name: "Vanishing Slash ",
    },
    {
        id: 529,
        name: "Shadow Leap",
    },
    {
        id: 530,
        name: "Shadow Slash ",
    },
    {
        id: 531,
        name: "Cicada Skin Sheeding",
    },
    {
        id: 532,
        name: "Mirror Image ",
    },
    {
        id: 533,
        name: "Spirit of the Blade",
    },
    {
        id: 534,
        name: "Crimson Fire Petal",
    },
    {
        id: 535,
        name: "Crimson Fire Formation ",
    },
    {
        id: 536,
        name: "Raging Fire Dragon",
    },
    {
        id: 537,
        name: "Spear of Ice ",
    },
    {
        id: 538,
        name: "Hidden Water ",
    },
    {
        id: 539,
        name: "Ice Meteor ",
    },
    {
        id: 540,
        name: "Wind Blade ",
    },
    {
        id: 541,
        name: "Lightning Strike of Destruction",
    },
    {
        id: 542,
        name: "Kamaitachi ",
    },
    {
        id: 543,
        name: "Soul",
    },
    {
        id: 544,
        name: "Final Strike ",
    },
    {
        id: 653,
        name: "Earthquake ",
    },
    {
        id: 654,
        name: "Fire Breath",
    },
    {
        id: 655,
        name: "Ice Breath ",
    },
    {
        id: 656,
        name: "Thunder Breath  ",
    },
    {
        id: 657,
        name: "Acid Breath",
    },
    {
        id: 658,
        name: "Darkness Breath ",
    },
    {
        id: 659,
        name: "Dragon Fear",
    },
    {
        id: 660,
        name: "Bleeding",
    },
    {
        id: 661,
        name: "Pulse Strike ",
    },
    {
        id: 662,
        name: "Hell's Judgement",
    },
    {
        id: 663,
        name: "Wide Silence ",
    },
    {
        id: 664,
        name: "Wide Freeze",
    },
    {
        id: 665,
        name: "Wide Bleeding",
    },
    {
        id: 666,
        name: "Wide Petrify ",
    },
    {
        id: 667,
        name: "Wide Confusion  ",
    },
    {
        id: 668,
        name: "Wide Sleep ",
    },
    {
        id: 669,
        name: "Wide Sight ",
    },
    {
        id: 670,
        name: "Evil Land  ",
    },
    {
        id: 671,
        name: "Magic Mirror ",
    },
    {
        id: 672,
        name: "Slow Cast  ",
    },
    {
        id: 673,
        name: "Critical Wounds ",
    },
    {
        id: 674,
        name: "Expulsion  ",
    },
    {
        id: 675,
        name: "Stone Skin ",
    },
    {
        id: 676,
        name: "Anti Magic ",
    },
    {
        id: 677,
        name: "Wide Curse ",
    },
    {
        id: 678,
        name: "Wide Stun  ",
    },
    {
        id: 679,
        name: "Vampire Gift ",
    },
    {
        id: 680,
        name: "Wide Soul Drain ",
    },
    {
        id: 681,
        name: "Increase Weight Limit R",
    },
    {
        id: 682,
        name: "Talk",
    },
    {
        id: 683,
        name: "Hell Power ",
    },
    {
        id: 684,
        name: "Hell Dignity ",
    },
    {
        id: 685,
        name: "Invincible ",
    },
    {
        id: 686,
        name: "Invincible off  ",
    },
    {
        id: 687,
        name: "Full Heal  ",
    },
    {
        id: 688,
        name: "GM Sandman ",
    },
    {
        id: 689,
        name: "Party Blessing  ",
    },
    {
        id: 690,
        name: "Party Increase AGI",
    },
    {
        id: 691,
        name: "Party Assumptio ",
    },
    {
        id: 692,
        name: "Cat Cry",
    },
    {
        id: 693,
        name: "Party Flee ",
    },
    {
        id: 694,
        name: "Angel's Protection",
    },
    {
        id: 695,
        name: "Summer Night Dream",
    },
    {
        id: 696,
        name: "Change Undead",
    },
    {
        id: 697,
        name: "Reverse Orcish  ",
    },
    {
        id: 698,
        name: "Christmas Carol ",
    },
    {
        id: 699,
        name: "ALL SONKRAN",
    },
    {
        id: 1001,
        name: "Charge Attack",
    },
    {
        id: 1002,
        name: "Shrink",
    },
    {
        id: 1003,
        name: "Sonic Acceleration",
    },
    {
        id: 1004,
        name: "Throw Venom Knife",
    },
    {
        id: 1005,
        name: "Close Confine",
    },
    {
        id: 1006,
        name: "Sight Blaster",
    },
    {
        id: 1007,
        name: "Create Elemental Converter ",
    },
    {
        id: 1008,
        name: "Elemental Change Water ",
    },
    {
        id: 1009,
        name: "Phantasmic Arrow",
    },
    {
        id: 1010,
        name: "Pang Voice ",
    },
    {
        id: 1011,
        name: "Wink of Charm",
    },
    {
        id: 1012,
        name: "Unfair Trick ",
    },
    {
        id: 1013,
        name: "Greed",
    },
    {
        id: 1014,
        name: "Redemptio  ",
    },
    {
        id: 1015,
        name: "Ki Translation  ",
    },
    {
        id: 1016,
        name: "Ki Explosion ",
    },
    {
        id: 1017,
        name: "Elemental Change Earth ",
    },
    {
        id: 1018,
        name: "Elemental Change Fire  ",
    },
    {
        id: 1019,
        name: "Elemental Change Wind  ",
    },
    {
        id: 2001,
        name: "Enchant Blade",
    },
    {
        id: 2002,
        name: "Sonic Wave ",
    },
    {
        id: 2003,
        name: "Death Bound",
    },
    {
        id: 2004,
        name: "Hundred Spear",
    },
    {
        id: 2005,
        name: "Wind Cutter",
    },
    {
        id: 2006,
        name: "Ignition Break  ",
    },
    {
        id: 2007,
        name: "Dragon Training ",
    },
    {
        id: 2008,
        name: "Dragon Breath",
    },
    {
        id: 2009,
        name: "Dragon Howling  ",
    },
    {
        id: 2010,
        name: "Rune Mastery ",
    },
    {
        id: 2011,
        name: "Millenium Shield",
    },
    {
        id: 2012,
        name: "Crush Strike ",
    },
    {
        id: 2013,
        name: "Refresh",
    },
    {
        id: 2014,
        name: "Giant Growth ",
    },
    {
        id: 2015,
        name: "Stone Hard Skin ",
    },
    {
        id: 2016,
        name: "Vitality Activation",
    },
    {
        id: 2017,
        name: "Storm Blast",
    },
    {
        id: 2018,
        name: "Fighting Spirit ",
    },
    {
        id: 2019,
        name: "Abundance  ",
    },
    {
        id: 2020,
        name: "Phantom Thrust  ",
    },
    {
        id: 2021,
        name: "Venom Impress",
    },
    {
        id: 2022,
        name: "Cross Impact ",
    },
    {
        id: 2023,
        name: "Dark Illusion",
    },
    {
        id: 2024,
        name: "Research New Poison",
    },
    {
        id: 2025,
        name: "Create New Poison",
    },
    {
        id: 2026,
        name: "Antidote",
    },
    {
        id: 2027,
        name: "Poisoning Weapon",
    },
    {
        id: 2028,
        name: "Weapon Blocking ",
    },
    {
        id: 2029,
        name: "Counter Slash",
    },
    {
        id: 2030,
        name: "Weapon Crush ",
    },
    {
        id: 2031,
        name: "Venom Pressure  ",
    },
    {
        id: 2032,
        name: "Poison Smoke ",
    },
    {
        id: 2033,
        name: "Cloaking Exceed ",
    },
    {
        id: 2034,
        name: "Phantom Menace  ",
    },
    {
        id: 2035,
        name: "Hallucination Walk",
    },
    {
        id: 2036,
        name: "Rolling Cutter  ",
    },
    {
        id: 2037,
        name: "Cross Ripper Slasher",
    },
    {
        id: 2038,
        name: "Judex",
    },
    {
        id: 2039,
        name: "Ancilla",
    },
    {
        id: 2040,
        name: "Adoramus",
    },
    {
        id: 2041,
        name: "Crementia  ",
    },
    {
        id: 2042,
        name: "Canto Candidus  ",
    },
    {
        id: 2043,
        name: "Coluceo Heal ",
    },
    {
        id: 2044,
        name: "Epiclesis  ",
    },
    {
        id: 2045,
        name: "Praefatio  ",
    },
    {
        id: 2046,
        name: "Oratio",
    },
    {
        id: 2047,
        name: "Lauda Agnus",
    },
    {
        id: 2048,
        name: "Lauda Ramus",
    },
    {
        id: 2049,
        name: "Eucharistica ",
    },
    {
        id: 2050,
        name: "Renovatio  ",
    },
    {
        id: 2051,
        name: "Highness Heal",
    },
    {
        id: 2052,
        name: "Clearance  ",
    },
    {
        id: 2053,
        name: "Expiatio",
    },
    {
        id: 2054,
        name: "Duple Light",
    },
    {
        id: 2055,
        name: "Duple Light Melee",
    },
    {
        id: 2056,
        name: "Duple Light Magic",
    },
    {
        id: 2057,
        name: "Silentium  ",
    },
    {
        id: 2201,
        name: "White Imprison  ",
    },
    {
        id: 2202,
        name: "Soul Expansion  ",
    },
    {
        id: 2203,
        name: "Frosty Misty ",
    },
    {
        id: 2204,
        name: "Jack Frost ",
    },
    {
        id: 2205,
        name: "Marsh of Abyss  ",
    },
    {
        id: 2206,
        name: "Recognized Spell",
    },
    {
        id: 2207,
        name: "Sienna Execrate ",
    },
    {
        id: 2208,
        name: "Radius",
    },
    {
        id: 2209,
        name: "Stasis",
    },
    {
        id: 2210,
        name: "Drain Life ",
    },
    {
        id: 2211,
        name: "Crimson Rock ",
    },
    {
        id: 2212,
        name: "Hell Inferno ",
    },
    {
        id: 2213,
        name: "Comet",
    },
    {
        id: 2214,
        name: "Chain Lightning ",
    },
    {
        id: 2215,
        name: "Chain Lightning Attack ",
    },
    {
        id: 2216,
        name: "Earth Strain ",
    },
    {
        id: 2217,
        name: "Tetra Vortex ",
    },
    {
        id: 2218,
        name: "Tetra Vortex Fire",
    },
    {
        id: 2219,
        name: "Tetra Vortex Water",
    },
    {
        id: 2220,
        name: "Tetra Vortex Wind",
    },
    {
        id: 2221,
        name: "Tetra Vortex Earth",
    },
    {
        id: 2222,
        name: "Summon Fire Ball",
    },
    {
        id: 2223,
        name: "Summon Lightning Ball  ",
    },
    {
        id: 2224,
        name: "Summon Water Ball",
    },
    {
        id: 2225,
        name: "Summon Attack Fire",
    },
    {
        id: 2226,
        name: "Summon Attack Wind",
    },
    {
        id: 2227,
        name: "Summon Attack Water",
    },
    {
        id: 2228,
        name: "Summon Attack Earth",
    },
    {
        id: 2229,
        name: "Summon Stone ",
    },
    {
        id: 2230,
        name: "Release",
    },
    {
        id: 2231,
        name: "Reading Spellbook",
    },
    {
        id: 2232,
        name: "Freeze Spell ",
    },
    {
        id: 2233,
        name: "Arrow Storm",
    },
    {
        id: 2234,
        name: "Fear Breeze",
    },
    {
        id: 2235,
        name: "Ranger Main",
    },
    {
        id: 2236,
        name: "Aimed Bolt ",
    },
    {
        id: 2237,
        name: "Detonator  ",
    },
    {
        id: 2238,
        name: "Electric Shocker",
    },
    {
        id: 2239,
        name: "Cluster Bomb ",
    },
    {
        id: 2240,
        name: "Warg Mastery ",
    },
    {
        id: 2241,
        name: "Warg Rider ",
    },
    {
        id: 2242,
        name: "Warg Dash  ",
    },
    {
        id: 2243,
        name: "Warg Strike",
    },
    {
        id: 2244,
        name: "Warg Bite  ",
    },
    {
        id: 2245,
        name: "Tooth of Warg",
    },
    {
        id: 2246,
        name: "Sensitive Keen  ",
    },
    {
        id: 2247,
        name: "Camouflage ",
    },
    {
        id: 2248,
        name: "Research Trap",
    },
    {
        id: 2249,
        name: "Magenta Trap ",
    },
    {
        id: 2250,
        name: "Cobalt Trap",
    },
    {
        id: 2251,
        name: "Maize Trap ",
    },
    {
        id: 2252,
        name: "Verdure Trap ",
    },
    {
        id: 2253,
        name: "Firing Trap",
    },
    {
        id: 2254,
        name: "Icebound Trap",
    },
    {
        id: 2255,
        name: "Mado License ",
    },
    {
        id: 2256,
        name: "Boost Knuckle",
    },
    {
        id: 2257,
        name: "Pile Bunker // Need official range.  ",
    },
    {
        id: 2258,
        name: "Vulcan Arm ",
    },
    {
        id: 2259,
        name: "Flame Launcher  ",
    },
    {
        id: 2260,
        name: "Cold Slower",
    },
    {
        id: 2261,
        name: "Arm Cannon ",
    },
    {
        id: 2262,
        name: "Acceleration ",
    },
    {
        id: 2263,
        name: "Hovering",
    },
    {
        id: 2264,
        name: "Front-Side Slide",
    },
    {
        id: 2265,
        name: "Back-Side Slide ",
    },
    {
        id: 2266,
        name: "Mainframe Restructure  ",
    },
    {
        id: 2267,
        name: "Self Destruction",
    },
    {
        id: 2268,
        name: "Shape Shift",
    },
    {
        id: 2269,
        name: "Emergency Cool  ",
    },
    {
        id: 2270,
        name: "Infrared Scan",
    },
    {
        id: 2271,
        name: "Analyze",
    },
    {
        id: 2272,
        name: "Magnetic Field  ",
    },
    {
        id: 2273,
        name: "Neutral Barrier ",
    },
    {
        id: 2274,
        name: "Stealth Field",
    },
    {
        id: 2275,
        name: "Repair",
    },
    {
        id: 2276,
        name: "Axe Training ",
    },
    {
        id: 2277,
        name: "Research Fire/Earth",
    },
    {
        id: 2278,
        name: "Axe Boomerang",
    },
    {
        id: 2279,
        name: "Power Swing",
    },
    {
        id: 2280,
        name: "Axe Tornado",
    },
    {
        id: 2281,
        name: "FAW - Silver Sniper",
    },
    {
        id: 2282,
        name: "FAW - Magic Decoy",
    },
    {
        id: 2283,
        name: "FAW Removal",
    },
    {
        id: 2284,
        name: "Fatal Menace ",
    },
    {
        id: 2285,
        name: "Reproduce  ",
    },
    {
        id: 2286,
        name: "Auto Shadow Spell",
    },
    {
        id: 2287,
        name: "Shadow Form",
    },
    {
        id: 2288,
        name: "Triangle Shot",
    },
    {
        id: 2289,
        name: "Body Painting",
    },
    {
        id: 2290,
        name: "Invisibility ",
    },
    {
        id: 2291,
        name: "Deadly Infect",
    },
    {
        id: 2292,
        name: "Masquerade - Enervation",
    },
    {
        id: 2293,
        name: "Masquerade - Gloomy",
    },
    {
        id: 2294,
        name: "Masquerade - Ignorance ",
    },
    {
        id: 2295,
        name: "Masquerade - Laziness  ",
    },
    {
        id: 2296,
        name: "Masquerade - Unlucky",
    },
    {
        id: 2297,
        name: "Masquerade - Weakness  ",
    },
    {
        id: 2298,
        name: "Strip Accessory ",
    },
    {
        id: 2299,
        name: "Man Hole",
    },
    {
        id: 2300,
        name: "Dimension Door  ",
    },
    {
        id: 2301,
        name: "Chaos Panic",
    },
    {
        id: 2302,
        name: "Maelstrom  ",
    },
    {
        id: 2303,
        name: "Bloody Lust",
    },
    {
        id: 2304,
        name: "Feint Bomb ",
    },
    {
        id: 2307,
        name: "Cannon Spear ",
    },
    {
        id: 2308,
        name: "Banishing Point ",
    },
    {
        id: 2309,
        name: "Trample",
    },
    {
        id: 2310,
        name: "Shield Press ",
    },
    {
        id: 2311,
        name: "Reflect Damage  ",
    },
    {
        id: 2312,
        name: "Pinpoint Attack ",
    },
    {
        id: 2313,
        name: "Force of Vanguard",
    },
    {
        id: 2314,
        name: "Rage Burst ",
    },
    {
        id: 2315,
        name: "Shield Spell ",
    },
    {
        id: 2316,
        name: "Exceed Break ",
    },
    {
        id: 2317,
        name: "Over Brand ",
    },
    {
        id: 2318,
        name: "Prestige",
    },
    {
        id: 2319,
        name: "Banding",
    },
    {
        id: 2320,
        name: "Moon Slasher ",
    },
    {
        id: 2321,
        name: "Ray of Genesis  ",
    },
    {
        id: 2322,
        name: "Piety",
    },
    {
        id: 2323,
        name: "Earth Drive",
    },
    {
        id: 2324,
        name: "Hesperus Lit ",
    },
    {
        id: 2325,
        name: "Inspiration",
    },
    {
        id: 2326,
        name: "Dragon Combo ",
    },
    {
        id: 2327,
        name: "Sky Net Blow ",
    },
    {
        id: 2328,
        name: "Earth Shaker ",
    },
    {
        id: 2329,
        name: "Fallen Empire",
    },
    {
        id: 2330,
        name: "Tiger Canon",
    },
    {
        id: 2331,
        name: "Hell Gate  ",
    },
    {
        id: 2332,
        name: "Rampage Blaster ",
    },
    {
        id: 2333,
        name: "Crescent Elbow  ",
    },
    {
        id: 2334,
        name: "Cursed Circle",
    },
    {
        id: 2335,
        name: "Lightning Walk  ",
    },
    {
        id: 2336,
        name: "Knuckle Arrow",
    },
    {
        id: 2337,
        name: "Windmill",
    },
    {
        id: 2338,
        name: "Raising Dragon  ",
    },
    {
        id: 2339,
        name: "Gentle Touch// What is this for? o.O [LimitLine] ",
    },
    {
        id: 2340,
        name: "Assimilate Power",
    },
    {
        id: 2341,
        name: "Power Velocity  ",
    },
    {
        id: 2342,
        name: "Crescent Elbow Autospell ",
    },
    {
        id: 2343,
        name: "Gate of Hell ",
    },
    {
        id: 2344,
        name: "Gentle Touch - Quiet",
    },
    {
        id: 2345,
        name: "Gentle Touch - Cure",
    },
    {
        id: 2346,
        name: "Gentle Touch - Energy Drain",
    },
    {
        id: 2347,
        name: "Gentle Touch - Change  ",
    },
    {
        id: 2348,
        name: "Gentle Touch - Revitalize",
    },
    {
        id: 2350,
        name: "Swing Dance",
    },
    {
        id: 2351,
        name: "Symphony of Lovers",
    },
    {
        id: 2352,
        name: "Moonlit Serenade",
    },
    {
        id: 2381,
        name: "Windmill Rush Attack",
    },
    {
        id: 2382,
        name: "Echo Song  ",
    },
    {
        id: 2383,
        name: "Harmonize  ",
    },
    {
        id: 2412,
        name: "Lesson",
    },
    {
        id: 2413,
        name: "Metallic Sound  ",
    },
    {
        id: 2414,
        name: "Reverberation",
    },
    {
        id: 2415,
        name: "Reverberation Melee",
    },
    {
        id: 2416,
        name: "Reverberation Magic",
    },
    {
        id: 2417,
        name: "Dominion Impulse",
    },
    {
        id: 2418,
        name: "Severe Rainstorm",
    },
    {
        id: 2419,
        name: "Poem of The Netherworld",
    },
    {
        id: 2420,
        name: "Voice of Siren  ",
    },
    {
        id: 2421,
        name: "Valley of Death ",
    },
    {
        id: 2422,
        name: "Deep Sleep Lullaby",
    },
    {
        id: 2423,
        name: "Circle of Nature's Sound ",
    },
    {
        id: 2424,
        name: "Improvised Song ",
    },
    {
        id: 2425,
        name: "Gloomy Day ",
    },
    {
        id: 2426,
        name: "Great Echo ",
    },
    {
        id: 2427,
        name: "Song of Mana ",
    },
    {
        id: 2428,
        name: "Dance With A Warg",
    },
    {
        id: 2429,
        name: "Sound of Destruction",
    },
    {
        id: 2430,
        name: "Saturday Night Fever",
    },
    {
        id: 2431,
        name: "Lerad's Dew",
    },
    {
        id: 2432,
        name: "Melody of Sink  ",
    },
    {
        id: 2433,
        name: "Warcry of Beyond",
    },
    {
        id: 2434,
        name: "Unlimited Humming Voice",
    },
    {
        id: 2443,
        name: "Fire Walk  ",
    },
    {
        id: 2444,
        name: "Electric Walk",
    },
    {
        id: 2445,
        name: "Spell Fist ",
    },
    {
        id: 2446,
        name: "Earth Grave",
    },
    {
        id: 2447,
        name: "Diamond Dust ",
    },
    {
        id: 2448,
        name: "Poison Buster",
    },
    {
        id: 2449,
        name: "Psychic Wave ",
    },
    {
        id: 2450,
        name: "Cloud Kill ",
    },
    {
        id: 2451,
        name: "Striking",
    },
    {
        id: 2452,
        name: "Warmer",
    },
    {
        id: 2453,
        name: "Vacuum Extreme  ",
    },
    {
        id: 2454,
        name: "Varetyr Spear",
    },
    {
        id: 2455,
        name: "Arrullo",
    },
    {
        id: 2456,
        name: "Spirit Control  ",
    },
    {
        id: 2457,
        name: "Summon Fire Spirit Agni",
    },
    {
        id: 2458,
        name: "Summon Water Spirit Aqua ",
    },
    {
        id: 2459,
        name: "Summon Wind Spirit Ventus",
    },
    {
        id: 2460,
        name: "Summon Earth Spirit Tera ",
    },
    {
        id: 2461,
        name: "Elemental Action",
    },
    {
        id: 2462,
        name: "Four Spirit Analysis",
    },
    {
        id: 2463,
        name: "Spirit Sympathy ",
    },
    {
        id: 2464,
        name: "Spirit Recovery ",
    },
    {
        id: 2465,
        name: "Fire Insignia",
    },
    {
        id: 2466,
        name: "Water Insignia  ",
    },
    {
        id: 2467,
        name: "Wind Insignia",
    },
    {
        id: 2468,
        name: "Earth Insignia  ",
    },
    {
        id: 2472,
        name: "End Mark",
    },
    {
        id: 2474,
        name: "Sword Training  ",
    },
    {
        id: 2475,
        name: "Cart Remodeling ",
    },
    {
        id: 2476,
        name: "Cart Tornado ",
    },
    {
        id: 2477,
        name: "Cart Cannon",
    },
    {
        id: 2478,
        name: "Cart Boost ",
    },
    {
        id: 2479,
        name: "Thorn Trap ",
    },
    {
        id: 2480,
        name: "Blood Sucker ",
    },
    {
        id: 2481,
        name: "Spore Explosion ",
    },
    {
        id: 2482,
        name: "Wall of Thorns  ",
    },
    {
        id: 2483,
        name: "Crazy Weed ",
    },
    {
        id: 2484,
        name: "Crazy Weed Attack",
    },
    {
        id: 2485,
        name: "Demonic Fire ",
    },
    {
        id: 2486,
        name: "Fire Expansion  ",
    },
    {
        id: 2487,
        name: "Fire Expansion Smoke Powder",
    },
    {
        id: 2488,
        name: "Fire Expansion Tear Gas",
    },
    {
        id: 2489,
        name: "Fire Expansion Acid",
    },
    {
        id: 2490,
        name: "Hell's Plant ",
    },
    {
        id: 2491,
        name: "Hell's Plant Attack",
    },
    {
        id: 2492,
        name: "Howling of Mandragora  ",
    },
    {
        id: 2493,
        name: "Sling Item ",
    },
    {
        id: 2494,
        name: "Change Material ",
    },
    {
        id: 2495,
        name: "Mix Cooking",
    },
    {
        id: 2496,
        name: "Create Bomb",
    },
    {
        id: 2497,
        name: "Special Pharmacy",
    },
    {
        id: 2498,
        name: "Sling Item Attack",
    },
    {
        id: 2515,
        name: "Sacrament  ",
    },
    {
        id: 2516,
        name: "Severe Rainstorm Melee ",
    },
    {
        id: 2517,
        name: "Howling of Lion ",
    },
    {
        id: 2518,
        name: "Ride In Lightening",
    },
    {
        id: 2519,
        name: "Overbrand Brandish",
    },
    {
        id: 2520,
        name: "Overbrand Plus Attack  ",
    },
    {
        id: 2533,
        name: "Odin's Recall",
    },
    {
        id: 2534,
        name: "Return To Eldicastes",
    },
    {
        id: 2535,
        name: "Purchase Shop",
    },
    {
        id: 2536,
        name: "Guardian's Recall",
    },
    {
        id: 2537,
        name: "Odin's Power ",
    },
    {
        id: 8001,
        name: "Healing Touch",
    },
    {
        id: 8002,
        name: "Avoid",
    },
    {
        id: 8003,
        name: "Brain Surgery",
    },
    {
        id: 8004,
        name: "Change",
    },
    {
        id: 8005,
        name: "Castling",
    },
    {
        id: 8006,
        name: "Defense",
    },
    {
        id: 8007,
        name: "Adamantium Skin ",
    },
    {
        id: 8008,
        name: "Bloodlust  ",
    },
    {
        id: 8009,
        name: "Moonlight  ",
    },
    {
        id: 8010,
        name: "Fleeting Move",
    },
    {
        id: 8011,
        name: "Speed",
    },
    {
        id: 8012,
        name: "S.B.R.44",
    },
    {
        id: 8013,
        name: "Caprice",
    },
    {
        id: 8014,
        name: "Benediction of Chaos",
    },
    {
        id: 8015,
        name: "Instruct",
    },
    {
        id: 8016,
        name: "Bio Explosion",
    },
    {
        id: 8201,
        name: "Bash",
    },
    {
        id: 8202,
        name: "Magnum Break ",
    },
    {
        id: 8203,
        name: "Bowling Bash ",
    },
    {
        id: 8204,
        name: "Parry",
    },
    {
        id: 8205,
        name: "Shield Reflect  ",
    },
    {
        id: 8206,
        name: "Frenzy",
    },
    {
        id: 8207,
        name: "Double Strafe",
    },
    {
        id: 8208,
        name: "Arrow Shower ",
    },
    {
        id: 8209,
        name: "Skid Trap  ",
    },
    {
        id: 8210,
        name: "Land Mine  ",
    },
    {
        id: 8211,
        name: "Sandman",
    },
    {
        id: 8212,
        name: "Freezing Trap",
    },
    {
        id: 8213,
        name: "Remove Trap",
    },
    {
        id: 8214,
        name: "Arrow Repel",
    },
    {
        id: 8215,
        name: "Focused Arrow Strike",
    },
    {
        id: 8216,
        name: "Pierce",
    },
    {
        id: 8217,
        name: "Brandish Spear  ",
    },
    {
        id: 8218,
        name: "Spiral Pierce",
    },
    {
        id: 8219,
        name: "Defending Aura  ",
    },
    {
        id: 8220,
        name: "Guard",
    },
    {
        id: 8221,
        name: "Sacrifice  ",
    },
    {
        id: 8222,
        name: "Magnificat ",
    },
    {
        id: 8223,
        name: "Two-Hand Quicken",
    },
    {
        id: 8224,
        name: "Sight",
    },
    {
        id: 8225,
        name: "Crash",
    },
    {
        id: 8226,
        name: "Regain",
    },
    {
        id: 8227,
        name: "Tender",
    },
    {
        id: 8228,
        name: "Benediction",
    },
    {
        id: 8229,
        name: "Recuperate ",
    },
    {
        id: 8230,
        name: "Mental Cure",
    },
    {
        id: 8231,
        name: "Compress",
    },
    {
        id: 8232,
        name: "Provoke",
    },
    {
        id: 8233,
        name: "Berserk",
    },
    {
        id: 8234,
        name: "Decrease AGI ",
    },
    {
        id: 8235,
        name: "Scapegoat  ",
    },
    {
        id: 8236,
        name: "Lex Divina ",
    },
    {
        id: 8237,
        name: "Sense",
    },
    {
        id: 8238,
        name: "Kyrie Eleison",
    },
    {
        id: 8239,
        name: "Blessing",
    },
    {
        id: 8240,
        name: "Increase Agility",
    },
    {
        id: 8401,
        name: "Circle of Fire  ",
    },
    {
        id: 8402,
        name: "Fire Cloak ",
    },
    {
        id: 8403,
        name: "Fire Mantle",
    },
    {
        id: 8404,
        name: "Water Screen ",
    },
    {
        id: 8405,
        name: "Water Drop ",
    },
    {
        id: 8406,
        name: "Water Barrier",
    },
    {
        id: 8407,
        name: "Wind Step  ",
    },
    {
        id: 8408,
        name: "Wind Curtain ",
    },
    {
        id: 8409,
        name: "Zephyr",
    },
    {
        id: 8410,
        name: "Solid Skin ",
    },
    {
        id: 8411,
        name: "Stone Shield ",
    },
    {
        id: 8412,
        name: "Power of Gaia",
    },
    {
        id: 8413,
        name: "Pyrotechnic",
    },
    {
        id: 8414,
        name: "Heater",
    },
    {
        id: 8415,
        name: "Tropic",
    },
    {
        id: 8416,
        name: "Aqua Play  ",
    },
    {
        id: 8417,
        name: "Cooler",
    },
    {
        id: 8418,
        name: "Cool Air",
    },
    {
        id: 8419,
        name: "Gust",
    },
    {
        id: 8420,
        name: "Blast",
    },
    {
        id: 8421,
        name: "Wild Storm ",
    },
    {
        id: 8422,
        name: "Petrology  ",
    },
    {
        id: 8423,
        name: "Cursed Soil",
    },
    {
        id: 8424,
        name: "Upheaval",
    },
    {
        id: 8425,
        name: "Fire Arrow ",
    },
    {
        id: 8426,
        name: "Fire Bomb  ",
    },
    {
        id: 8427,
        name: "Fire Bomb Attack",
    },
    {
        id: 8428,
        name: "Fire Wave  ",
    },
    {
        id: 8429,
        name: "Fire Wave Attack",
    },
    {
        id: 8430,
        name: "Ice Needle ",
    },
    {
        id: 8431,
        name: "Water Screw",
    },
    {
        id: 8432,
        name: "Water Screw Attack",
    },
    {
        id: 8433,
        name: "Tidal Weapon",
    },
    {
        id: 8434,
        name: "Wind Slasher",
    },
    {
        id: 8435,
        name: "Hurricane Rage",
    },
    {
        id: 8436,
        name: "Hurricane Rage Attack ",
    },
    {
        id: 8437,
        name: "Typhoon Missile",
    },
    {
        id: 8438,
        name: "Typhoon Missile Attack",
    },
    {
        id: 8439,
        name: "Stone Hammer",
    },
    {
        id: 8440,
        name: "Rock Launcher",
    },
    {
        id: 8441,
        name: "Rock Launcher Attack",
    },
    {
        id: 8442,
        name: "Stone Rain ",
    },
    {
        id: 10000,
        name: "Official Guild Approval",
    },
    {
        id: 10001,
        name: "Kafra Contract",
    },
    {
        id: 10002,
        name: "Guardian Research",
    },
    {
        id: 10003,
        name: "Strengthen Guardians",
    },
    {
        id: 10004,
        name: "Guild Extension",
    },
    {
        id: 10005,
        name: "Guild's Glory ",
    },
    {
        id: 10006,
        name: "Great Leadership",
    },
    {
        id: 10007,
        name: "Glorious Wounds",
    },
    {
        id: 10008,
        name: "Cold Heart",
    },
    {
        id: 10009,
        name: "Sharp Gaze",
    },
    {
        id: 10010,
        name: "Battle Orders",
    },
    {
        id: 10011,
        name: "Regeneration",
    },
    {
        id: 10012,
        name: "Restoration",
    },
    {
        id: 10013,
        name: "Urgent Call",
    },
    {
        id: 10014,
        name: "Permanent Development",
    },
];

function mapname2bgname(map_name) {
    var bg_name;
    const mapNames = {
        "gvg_cas01"     :   "Castle Siege",
        "bg_koe"        :   "King of The Emperium",
        "bg_dte"        :   "Defend the Emperium",
        "dragon_cas"    :   "Battle Zone",
        "arena_01"      :   "TvT",
        "bg_ctf"        :   "Capture The Flag",
        "bg_ti"         :   "Triple Inferno",
        "arug_cas06"    :   "Conquest",
        "schg_cas07"    :   "Conquest",
        "schg_cas06"    :   "Conquest"
    };

    bg_name = mapNames[map_name] || bg_name;
    return bg_name;
}

function get_job_name(class_id) {
    const job = job_name.find((element) => element.id == class_id);
    if (job) {
        return job.name;
    }
}

function get_skill_name(skill_id) {
    const skill = skill_name.find((element) => element.id == skill_id);
    if (skill) {
        return skill.name;
    }
}

function getFormatNumber(number) {
    return new Intl.NumberFormat().format(number);
}

function isOnline(status) {
    return status == 1 ? "Online" : "Offline";
}

function padTo2Digits(num) {
    return num.toString().padStart(2, "0");
}
function formatDate(date) {
    return (
        [
            date.getFullYear(),
            padTo2Digits(date.getMonth() + 1),
            padTo2Digits(date.getDate()),
        ].join("-") +
        " " +
        [
            padTo2Digits(date.getHours()),
            padTo2Digits(date.getMinutes()),
            padTo2Digits(date.getSeconds()),
        ].join(":")
    );
}

function secondsToDh(d) {
    var seconds = Number(d);
    var d = Math.floor(seconds / (3600 * 24));
    var h = Math.floor((seconds % (3600 * 24)) / 3600);

    var dDisplay = d > 0 ? d + (d == 1 ? " day, " : " days ") : "";
    var hDisplay = h > 0 ? h + (h == 1 ? " hour " : " hours ") : "";
    return dDisplay + hDisplay;
}
export {
    country_data,
    rank_woe_type,
    ranking_jobs,
    whosell_type,
    itemdb_type,
    get_job_name,
    get_skill_name,
    isOnline,
    getFormatNumber,
    formatDate,
    mapname2bgname,
    secondsToDh,
};
