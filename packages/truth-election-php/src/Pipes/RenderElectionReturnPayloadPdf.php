<?php

namespace TruthElection\Pipes;

use TruthElection\Data\FinalizeErContext;

class RenderElectionReturnPayloadPdf
{
    protected function getPayload(FinalizeErContext $ctx): array
    {
        $data = <<<XXX
{
    "templateName": "core:precinct/er_qr/template",
    "format": "pdf",
    "data": {
        "tallyMeta": {
            "id": "01997c2a-6baa-7347-8a29-8073ec501d2d",
            "code": "317537",
            "precinct": {
                "code": "CURRIMAO-001",
                "location_name": "Currimao National High School",
                "latitude": 17.993217,
                "longitude": 120.488902,
                "electoral_inspectors": [
                    {
                        "id": "uuid-juan",
                        "name": "Juan dela Cruz",
                        "role": "chairperson"
                    },
                    {
                        "id": "uuid-maria",
                        "name": "Maria Santos",
                        "role": "member"
                    },
                    {
                        "id": "uuid-pedro",
                        "name": "Pedro Reyes",
                        "role": "member"
                    }
                ],
                "watchers_count": 6,
                "precincts_count": null,
                "registered_voters_count": 801,
                "actual_voters_count": 701,
                "ballots_in_box_count": 696,
                "unused_ballots_count": 106,
                "spoiled_ballots_count": null,
                "void_ballots_count": null,
                "closed_at": null,
                "ballots": [
                    {
                        "precinct_code": "CURRIMAO-001",
                        "code": "BAL-000",
                        "votes": [
                            {
                                "position": {
                                    "code": "PRESIDENT",
                                    "name": "President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "LD_001",
                                        "name": "Leonardo DiCaprio",
                                        "alias": "LD",
                                        "position": {
                                            "code": "PRESIDENT",
                                            "name": "President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-PRESIDENT",
                                    "name": "Vice President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "TH_001",
                                        "name": "Tom Hanks",
                                        "alias": "TH",
                                        "position": {
                                            "code": "VICE-PRESIDENT",
                                            "name": "Vice President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                },
                                "candidates": [
                                    {
                                        "code": "JD_001",
                                        "name": "Johnny Depp",
                                        "alias": "JD",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "CT_010",
                                        "name": "Charlize Theron",
                                        "alias": "CT",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "KR_011",
                                        "name": "Keanu Reeves",
                                        "alias": "KR",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "NK_012",
                                        "name": "Nicole Kidman",
                                        "alias": "NK",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "ES_002",
                                        "name": "Emma Stone",
                                        "alias": "ES",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MF_003",
                                        "name": "Morgan Freeman",
                                        "alias": "MF",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "JL_004",
                                        "name": "Jennifer Lawrence",
                                        "alias": "JL",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "CB_005",
                                        "name": "Christian Bale",
                                        "alias": "CB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "SB_006",
                                        "name": "Sandra Bullock",
                                        "alias": "SB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "WS_007",
                                        "name": "Will Smith",
                                        "alias": "WS",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "JR_008",
                                        "name": "Julia Roberts",
                                        "alias": "JR",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MD_009",
                                        "name": "Matt Damon",
                                        "alias": "MD",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "GOVERNOR-ILN",
                                    "name": "Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "EN_001",
                                        "name": "Edward Norton",
                                        "alias": "EN",
                                        "position": {
                                            "code": "GOVERNOR-ILN",
                                            "name": "Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-GOVERNOR-ILN",
                                    "name": "Vice Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "NW_001",
                                        "name": "Naomi Watts",
                                        "alias": "NW",
                                        "position": {
                                            "code": "VICE-GOVERNOR-ILN",
                                            "name": "Vice Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                },
                                "candidates": [
                                    {
                                        "code": "RW_001",
                                        "name": "Reese Witherspoon",
                                        "alias": "RW",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    },
                                    {
                                        "code": "HB_002",
                                        "name": "Halle Berry",
                                        "alias": "HB",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-ILN-1",
                                    "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                    "level": "district",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JF_001",
                                        "name": "Jodie Foster",
                                        "alias": "JF",
                                        "position": {
                                            "code": "REPRESENTATIVE-ILN-1",
                                            "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                            "level": "district",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "BC_001",
                                        "name": "Billy Crudup",
                                        "alias": "BC",
                                        "position": {
                                            "code": "MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                },
                                "candidates": [
                                    {
                                        "code": "ER_001",
                                        "name": "Eddie Redmayne",
                                        "alias": "ER",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SG_002",
                                        "name": "Stephen Graham",
                                        "alias": "SG",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SR_003",
                                        "name": "Saoirse Ronan",
                                        "alias": "SR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MC_004",
                                        "name": "Marion Cotillard",
                                        "alias": "MC",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MS_005",
                                        "name": "Michael Shannon",
                                        "alias": "MS",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "CE_006",
                                        "name": "Chiwetel Ejiofor",
                                        "alias": "CE",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "GMR_007",
                                        "name": "Gugu Mbatha-Raw",
                                        "alias": "GMR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "DO_008",
                                        "name": "David Oyelowo",
                                        "alias": "DO",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "precinct_code": "CURRIMAO-001",
                        "code": "BAL-001",
                        "votes": [
                            {
                                "position": {
                                    "code": "PRESIDENT",
                                    "name": "President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "AJ_006",
                                        "name": "Angelina Jolie",
                                        "alias": "AJ",
                                        "position": {
                                            "code": "PRESIDENT",
                                            "name": "President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-PRESIDENT",
                                    "name": "Vice President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "TH_001",
                                        "name": "Tom Hanks",
                                        "alias": "TH",
                                        "position": {
                                            "code": "VICE-PRESIDENT",
                                            "name": "Vice President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                },
                                "candidates": [
                                    {
                                        "code": "ES_002",
                                        "name": "Emma Stone",
                                        "alias": "ES",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "LN_048",
                                        "name": "Lupita Nyong'o",
                                        "alias": "LN",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "AA_018",
                                        "name": "Amy Adams",
                                        "alias": "AA",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "GG_016",
                                        "name": "Gal Gadot",
                                        "alias": "GG",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "BC_015",
                                        "name": "Benedict Cumberbatch",
                                        "alias": "BC",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MD_009",
                                        "name": "Matt Damon",
                                        "alias": "MD",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "WS_007",
                                        "name": "Will Smith",
                                        "alias": "WS",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MA_035",
                                        "name": "Mahershala Ali",
                                        "alias": "MA",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "SB_006",
                                        "name": "Sandra Bullock",
                                        "alias": "SB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "FP_038",
                                        "name": "Florence Pugh",
                                        "alias": "FP",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "OS_028",
                                        "name": "Octavia Spencer",
                                        "alias": "OS",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MF_003",
                                        "name": "Morgan Freeman",
                                        "alias": "MF",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-PARTY-LIST",
                                    "name": "Party-list Representative",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "THE_MATRIX_008",
                                        "name": "The Matrix",
                                        "alias": "the_matrix",
                                        "position": {
                                            "code": "REPRESENTATIVE-PARTY-LIST",
                                            "name": "Party-list Representative",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "GOVERNOR-ILN",
                                    "name": "Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "EN_001",
                                        "name": "Edward Norton",
                                        "alias": "EN",
                                        "position": {
                                            "code": "GOVERNOR-ILN",
                                            "name": "Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-GOVERNOR-ILN",
                                    "name": "Vice Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "MF_002",
                                        "name": "Michael Fassbender",
                                        "alias": "MF",
                                        "position": {
                                            "code": "VICE-GOVERNOR-ILN",
                                            "name": "Vice Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                },
                                "candidates": [
                                    {
                                        "code": "DP_004",
                                        "name": "Dev Patel",
                                        "alias": "DP",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    },
                                    {
                                        "code": "BDT_005",
                                        "name": "Benicio Del Toro",
                                        "alias": "BDT",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-ILN-1",
                                    "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                    "level": "district",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JF_001",
                                        "name": "Jodie Foster",
                                        "alias": "JF",
                                        "position": {
                                            "code": "REPRESENTATIVE-ILN-1",
                                            "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                            "level": "district",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "EW_003",
                                        "name": "Emily Watson",
                                        "alias": "EW",
                                        "position": {
                                            "code": "MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JKS_001",
                                        "name": "J.K. Simmons",
                                        "alias": "JKS",
                                        "position": {
                                            "code": "VICE-MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                },
                                "candidates": [
                                    {
                                        "code": "ER_001",
                                        "name": "Eddie Redmayne",
                                        "alias": "ER",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SG_002",
                                        "name": "Stephen Graham",
                                        "alias": "SG",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SR_003",
                                        "name": "Saoirse Ronan",
                                        "alias": "SR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MC_004",
                                        "name": "Marion Cotillard",
                                        "alias": "MC",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MS_005",
                                        "name": "Michael Shannon",
                                        "alias": "MS",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "CE_006",
                                        "name": "Chiwetel Ejiofor",
                                        "alias": "CE",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "GMR_007",
                                        "name": "Gugu Mbatha-Raw",
                                        "alias": "GMR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "DO_008",
                                        "name": "David Oyelowo",
                                        "alias": "DO",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "precinct_code": "CURRIMAO-001",
                        "code": "BAL-002",
                        "votes": [
                            {
                                "position": {
                                    "code": "PRESIDENT",
                                    "name": "President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "AJ_006",
                                        "name": "Angelina Jolie",
                                        "alias": "AJ",
                                        "position": {
                                            "code": "PRESIDENT",
                                            "name": "President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-PRESIDENT",
                                    "name": "Vice President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "AH_006",
                                        "name": "Anne Hathaway",
                                        "alias": "AH",
                                        "position": {
                                            "code": "VICE-PRESIDENT",
                                            "name": "Vice President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                },
                                "candidates": [
                                    {
                                        "code": "JC_045",
                                        "name": "Jessica Chastain",
                                        "alias": "JC",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MA_035",
                                        "name": "Mahershala Ali",
                                        "alias": "MA",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "JM_027",
                                        "name": "Jason Momoa",
                                        "alias": "JM",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "BC_015",
                                        "name": "Benedict Cumberbatch",
                                        "alias": "BC",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "RG_029",
                                        "name": "Ryan Gosling",
                                        "alias": "RG",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "FP_038",
                                        "name": "Florence Pugh",
                                        "alias": "FP",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "AA_018",
                                        "name": "Amy Adams",
                                        "alias": "AA",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "JD_001",
                                        "name": "Johnny Depp",
                                        "alias": "JD",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "KR_011",
                                        "name": "Keanu Reeves",
                                        "alias": "KR",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "OS_028",
                                        "name": "Octavia Spencer",
                                        "alias": "OS",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "EB_022",
                                        "name": "Emily Blunt",
                                        "alias": "EB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "A_044",
                                        "name": "Awkwafina",
                                        "alias": "A",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-PARTY-LIST",
                                    "name": "Party-list Representative",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "TENET_040",
                                        "name": "Tenet",
                                        "alias": "tenet",
                                        "position": {
                                            "code": "REPRESENTATIVE-PARTY-LIST",
                                            "name": "Party-list Representative",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "GOVERNOR-ILN",
                                    "name": "Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "EN_001",
                                        "name": "Edward Norton",
                                        "alias": "EN",
                                        "position": {
                                            "code": "GOVERNOR-ILN",
                                            "name": "Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-GOVERNOR-ILN",
                                    "name": "Vice Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "DK_003",
                                        "name": "Daniel Kaluuya",
                                        "alias": "DK",
                                        "position": {
                                            "code": "VICE-GOVERNOR-ILN",
                                            "name": "Vice Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                },
                                "candidates": [
                                    {
                                        "code": "HB_002",
                                        "name": "Halle Berry",
                                        "alias": "HB",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    },
                                    {
                                        "code": "RW_001",
                                        "name": "Reese Witherspoon",
                                        "alias": "RW",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-ILN-1",
                                    "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                    "level": "district",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "DC_004",
                                        "name": "Don Cheadle",
                                        "alias": "DC",
                                        "position": {
                                            "code": "REPRESENTATIVE-ILN-1",
                                            "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                            "level": "district",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "BC_001",
                                        "name": "Billy Crudup",
                                        "alias": "BC",
                                        "position": {
                                            "code": "MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JL_002",
                                        "name": "Jared Leto",
                                        "alias": "JL",
                                        "position": {
                                            "code": "VICE-MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                },
                                "candidates": [
                                    {
                                        "code": "ER_001",
                                        "name": "Eddie Redmayne",
                                        "alias": "ER",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SG_002",
                                        "name": "Stephen Graham",
                                        "alias": "SG",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SR_003",
                                        "name": "Saoirse Ronan",
                                        "alias": "SR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MC_004",
                                        "name": "Marion Cotillard",
                                        "alias": "MC",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MS_005",
                                        "name": "Michael Shannon",
                                        "alias": "MS",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "CE_006",
                                        "name": "Chiwetel Ejiofor",
                                        "alias": "CE",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "GMR_007",
                                        "name": "Gugu Mbatha-Raw",
                                        "alias": "GMR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "DO_008",
                                        "name": "David Oyelowo",
                                        "alias": "DO",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "precinct_code": "CURRIMAO-001",
                        "code": "BAL-003",
                        "votes": [
                            {
                                "position": {
                                    "code": "PRESIDENT",
                                    "name": "President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "DW_003",
                                        "name": "Denzel Washington",
                                        "alias": "DW",
                                        "position": {
                                            "code": "PRESIDENT",
                                            "name": "President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-PRESIDENT",
                                    "name": "Vice President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "RDJ_005",
                                        "name": "Robert Downey Jr.",
                                        "alias": "RDJ",
                                        "position": {
                                            "code": "VICE-PRESIDENT",
                                            "name": "Vice President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                },
                                "candidates": [
                                    {
                                        "code": "JG_017",
                                        "name": "Jake Gyllenhaal",
                                        "alias": "JG",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MF_003",
                                        "name": "Morgan Freeman",
                                        "alias": "MF",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "TH_047",
                                        "name": "Tom Holland",
                                        "alias": "TH",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "SH_030",
                                        "name": "Salma Hayek",
                                        "alias": "SH",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MA_035",
                                        "name": "Mahershala Ali",
                                        "alias": "MA",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "JR_008",
                                        "name": "Julia Roberts",
                                        "alias": "JR",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "CB_025",
                                        "name": "Chadwick Boseman",
                                        "alias": "CB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "SB_006",
                                        "name": "Sandra Bullock",
                                        "alias": "SB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "EG_036",
                                        "name": "Eva Green",
                                        "alias": "EG",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "DC_019",
                                        "name": "Daniel Craig",
                                        "alias": "DC",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "RM_040",
                                        "name": "Rami Malek",
                                        "alias": "RM",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "RM_024",
                                        "name": "Rachel McAdams",
                                        "alias": "RM",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-PARTY-LIST",
                                    "name": "Party-list Representative",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "LUCA_148",
                                        "name": "Luca",
                                        "alias": "luca",
                                        "position": {
                                            "code": "REPRESENTATIVE-PARTY-LIST",
                                            "name": "Party-list Representative",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "GOVERNOR-ILN",
                                    "name": "Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "EN_001",
                                        "name": "Edward Norton",
                                        "alias": "EN",
                                        "position": {
                                            "code": "GOVERNOR-ILN",
                                            "name": "Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-GOVERNOR-ILN",
                                    "name": "Vice Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "NW_001",
                                        "name": "Naomi Watts",
                                        "alias": "NW",
                                        "position": {
                                            "code": "VICE-GOVERNOR-ILN",
                                            "name": "Vice Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                },
                                "candidates": [
                                    {
                                        "code": "RW_001",
                                        "name": "Reese Witherspoon",
                                        "alias": "RW",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    },
                                    {
                                        "code": "DJ_006",
                                        "name": "Dakota Johnson",
                                        "alias": "DJ",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-ILN-1",
                                    "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                    "level": "district",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "RW_002",
                                        "name": "Rachel Weisz",
                                        "alias": "RW",
                                        "position": {
                                            "code": "REPRESENTATIVE-ILN-1",
                                            "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                            "level": "district",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "LJ_002",
                                        "name": "Lily James",
                                        "alias": "LJ",
                                        "position": {
                                            "code": "MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JKS_001",
                                        "name": "J.K. Simmons",
                                        "alias": "JKS",
                                        "position": {
                                            "code": "VICE-MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                },
                                "candidates": [
                                    {
                                        "code": "ER_001",
                                        "name": "Eddie Redmayne",
                                        "alias": "ER",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SG_002",
                                        "name": "Stephen Graham",
                                        "alias": "SG",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SR_003",
                                        "name": "Saoirse Ronan",
                                        "alias": "SR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MC_004",
                                        "name": "Marion Cotillard",
                                        "alias": "MC",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MS_005",
                                        "name": "Michael Shannon",
                                        "alias": "MS",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "CE_006",
                                        "name": "Chiwetel Ejiofor",
                                        "alias": "CE",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "GMR_007",
                                        "name": "Gugu Mbatha-Raw",
                                        "alias": "GMR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "DO_008",
                                        "name": "David Oyelowo",
                                        "alias": "DO",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "precinct_code": "CURRIMAO-001",
                        "code": "BAL-004",
                        "votes": [
                            {
                                "position": {
                                    "code": "PRESIDENT",
                                    "name": "President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "SJ_002",
                                        "name": "Scarlett Johansson",
                                        "alias": "SJ",
                                        "position": {
                                            "code": "PRESIDENT",
                                            "name": "President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-PRESIDENT",
                                    "name": "Vice President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "TH_001",
                                        "name": "Tom Hanks",
                                        "alias": "TH",
                                        "position": {
                                            "code": "VICE-PRESIDENT",
                                            "name": "Vice President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                },
                                "candidates": [
                                    {
                                        "code": "CB_025",
                                        "name": "Chadwick Boseman",
                                        "alias": "CB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "AA_018",
                                        "name": "Amy Adams",
                                        "alias": "AA",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "SH_030",
                                        "name": "Salma Hayek",
                                        "alias": "SH",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "ATJ_041",
                                        "name": "Anya Taylor-Joy",
                                        "alias": "ATJ",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "RM_024",
                                        "name": "Rachel McAdams",
                                        "alias": "RM",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "KR_011",
                                        "name": "Keanu Reeves",
                                        "alias": "KR",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "AG_046",
                                        "name": "Andrew Garfield",
                                        "alias": "AG",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "CE_023",
                                        "name": "Chris Evans",
                                        "alias": "CE",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "ZS_014",
                                        "name": "Zoe Saldana",
                                        "alias": "ZS",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "BC_049",
                                        "name": "Bryan Cranston",
                                        "alias": "BC",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "CB_005",
                                        "name": "Christian Bale",
                                        "alias": "CB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "PP_039",
                                        "name": "Pedro Pascal",
                                        "alias": "PP",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-PARTY-LIST",
                                    "name": "Party-list Representative",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "THE_MARTIAN_044",
                                        "name": "The Martian",
                                        "alias": "the_martian",
                                        "position": {
                                            "code": "REPRESENTATIVE-PARTY-LIST",
                                            "name": "Party-list Representative",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "GOVERNOR-ILN",
                                    "name": "Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "RP_003",
                                        "name": "Rosamund Pike",
                                        "alias": "RP",
                                        "position": {
                                            "code": "GOVERNOR-ILN",
                                            "name": "Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-GOVERNOR-ILN",
                                    "name": "Vice Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "MF_002",
                                        "name": "Michael Fassbender",
                                        "alias": "MF",
                                        "position": {
                                            "code": "VICE-GOVERNOR-ILN",
                                            "name": "Vice Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                },
                                "candidates": [
                                    {
                                        "code": "RW_003",
                                        "name": "Robin Wright",
                                        "alias": "RW",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    },
                                    {
                                        "code": "DP_004",
                                        "name": "Dev Patel",
                                        "alias": "DP",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-ILN-1",
                                    "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                    "level": "district",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "DC_004",
                                        "name": "Don Cheadle",
                                        "alias": "DC",
                                        "position": {
                                            "code": "REPRESENTATIVE-ILN-1",
                                            "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                            "level": "district",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "LJ_002",
                                        "name": "Lily James",
                                        "alias": "LJ",
                                        "position": {
                                            "code": "MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JF_003",
                                        "name": "Jamie Foxx",
                                        "alias": "JF",
                                        "position": {
                                            "code": "VICE-MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                },
                                "candidates": [
                                    {
                                        "code": "ER_001",
                                        "name": "Eddie Redmayne",
                                        "alias": "ER",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SG_002",
                                        "name": "Stephen Graham",
                                        "alias": "SG",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SR_003",
                                        "name": "Saoirse Ronan",
                                        "alias": "SR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MC_004",
                                        "name": "Marion Cotillard",
                                        "alias": "MC",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MS_005",
                                        "name": "Michael Shannon",
                                        "alias": "MS",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "CE_006",
                                        "name": "Chiwetel Ejiofor",
                                        "alias": "CE",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "GMR_007",
                                        "name": "Gugu Mbatha-Raw",
                                        "alias": "GMR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "DO_008",
                                        "name": "David Oyelowo",
                                        "alias": "DO",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "precinct_code": "CURRIMAO-001",
                        "code": "BAL-005",
                        "votes": [
                            {
                                "position": {
                                    "code": "PRESIDENT",
                                    "name": "President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "AJ_006",
                                        "name": "Angelina Jolie",
                                        "alias": "AJ",
                                        "position": {
                                            "code": "PRESIDENT",
                                            "name": "President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-PRESIDENT",
                                    "name": "Vice President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "RDJ_005",
                                        "name": "Robert Downey Jr.",
                                        "alias": "RDJ",
                                        "position": {
                                            "code": "VICE-PRESIDENT",
                                            "name": "Vice President of the Philippines",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                },
                                "candidates": [
                                    {
                                        "code": "BC_015",
                                        "name": "Benedict Cumberbatch",
                                        "alias": "BC",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "A_044",
                                        "name": "Awkwafina",
                                        "alias": "A",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MR_021",
                                        "name": "Mark Ruffalo",
                                        "alias": "MR",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "JL_004",
                                        "name": "Jennifer Lawrence",
                                        "alias": "JL",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "HM_050",
                                        "name": "Helen Mirren",
                                        "alias": "HM",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "MF_003",
                                        "name": "Morgan Freeman",
                                        "alias": "MF",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "LN_048",
                                        "name": "Lupita Nyong'o",
                                        "alias": "LN",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "TS_026",
                                        "name": "Tilda Swinton",
                                        "alias": "TS",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "AA_018",
                                        "name": "Amy Adams",
                                        "alias": "AA",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "CB_005",
                                        "name": "Christian Bale",
                                        "alias": "CB",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "ZS_014",
                                        "name": "Zoe Saldana",
                                        "alias": "ZS",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    },
                                    {
                                        "code": "TC_037",
                                        "name": "Timothee Chalamet",
                                        "alias": "TC",
                                        "position": {
                                            "code": "SENATOR",
                                            "name": "Senator of the Philippines",
                                            "level": "national",
                                            "count": 12
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-PARTY-LIST",
                                    "name": "Party-list Representative",
                                    "level": "national",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "THE_GREEN_MILE_081",
                                        "name": "The Green Mile",
                                        "alias": "the_green_mile",
                                        "position": {
                                            "code": "REPRESENTATIVE-PARTY-LIST",
                                            "name": "Party-list Representative",
                                            "level": "national",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "GOVERNOR-ILN",
                                    "name": "Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "EN_001",
                                        "name": "Edward Norton",
                                        "alias": "EN",
                                        "position": {
                                            "code": "GOVERNOR-ILN",
                                            "name": "Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-GOVERNOR-ILN",
                                    "name": "Vice Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JH_004",
                                        "name": "Jennifer Hudson",
                                        "alias": "JH",
                                        "position": {
                                            "code": "VICE-GOVERNOR-ILN",
                                            "name": "Vice Governor \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                },
                                "candidates": [
                                    {
                                        "code": "AD_007",
                                        "name": "Adam Driver",
                                        "alias": "AD",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    },
                                    {
                                        "code": "DJ_006",
                                        "name": "Dakota Johnson",
                                        "alias": "DJ",
                                        "position": {
                                            "code": "BOARD-MEMBER-ILN",
                                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                                            "level": "local",
                                            "count": 2
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "REPRESENTATIVE-ILN-1",
                                    "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                    "level": "district",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "DC_004",
                                        "name": "Don Cheadle",
                                        "alias": "DC",
                                        "position": {
                                            "code": "REPRESENTATIVE-ILN-1",
                                            "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                            "level": "district",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "BC_001",
                                        "name": "Billy Crudup",
                                        "alias": "BC",
                                        "position": {
                                            "code": "MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "VICE-MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                },
                                "candidates": [
                                    {
                                        "code": "JL_002",
                                        "name": "Jared Leto",
                                        "alias": "JL",
                                        "position": {
                                            "code": "VICE-MAYOR-ILN-CURRIMAO",
                                            "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 1
                                        }
                                    }
                                ]
                            },
                            {
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                },
                                "candidates": [
                                    {
                                        "code": "ER_001",
                                        "name": "Eddie Redmayne",
                                        "alias": "ER",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SG_002",
                                        "name": "Stephen Graham",
                                        "alias": "SG",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "SR_003",
                                        "name": "Saoirse Ronan",
                                        "alias": "SR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MC_004",
                                        "name": "Marion Cotillard",
                                        "alias": "MC",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "MS_005",
                                        "name": "Michael Shannon",
                                        "alias": "MS",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "CE_006",
                                        "name": "Chiwetel Ejiofor",
                                        "alias": "CE",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "GMR_007",
                                        "name": "Gugu Mbatha-Raw",
                                        "alias": "GMR",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    },
                                    {
                                        "code": "DO_008",
                                        "name": "David Oyelowo",
                                        "alias": "DO",
                                        "position": {
                                            "code": "COUNCILOR-ILN-CURRIMAO",
                                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                            "level": "local",
                                            "count": 8
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            "tallies": [
                {
                    "position_code": "PRESIDENT",
                    "candidate_code": "AJ_006",
                    "candidate_name": "Angelina Jolie",
                    "count": 3
                },
                {
                    "position_code": "PRESIDENT",
                    "candidate_code": "LD_001",
                    "candidate_name": "Leonardo DiCaprio",
                    "count": 1
                },
                {
                    "position_code": "PRESIDENT",
                    "candidate_code": "DW_003",
                    "candidate_name": "Denzel Washington",
                    "count": 1
                },
                {
                    "position_code": "PRESIDENT",
                    "candidate_code": "SJ_002",
                    "candidate_name": "Scarlett Johansson",
                    "count": 1
                },
                {
                    "position_code": "VICE-PRESIDENT",
                    "candidate_code": "TH_001",
                    "candidate_name": "Tom Hanks",
                    "count": 3
                },
                {
                    "position_code": "VICE-PRESIDENT",
                    "candidate_code": "RDJ_005",
                    "candidate_name": "Robert Downey Jr.",
                    "count": 2
                },
                {
                    "position_code": "VICE-PRESIDENT",
                    "candidate_code": "AH_006",
                    "candidate_name": "Anne Hathaway",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "MF_003",
                    "candidate_name": "Morgan Freeman",
                    "count": 4
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "AA_018",
                    "candidate_name": "Amy Adams",
                    "count": 4
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "KR_011",
                    "candidate_name": "Keanu Reeves",
                    "count": 3
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "CB_005",
                    "candidate_name": "Christian Bale",
                    "count": 3
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "SB_006",
                    "candidate_name": "Sandra Bullock",
                    "count": 3
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "BC_015",
                    "candidate_name": "Benedict Cumberbatch",
                    "count": 3
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "MA_035",
                    "candidate_name": "Mahershala Ali",
                    "count": 3
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "JD_001",
                    "candidate_name": "Johnny Depp",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "ES_002",
                    "candidate_name": "Emma Stone",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "JL_004",
                    "candidate_name": "Jennifer Lawrence",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "WS_007",
                    "candidate_name": "Will Smith",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "JR_008",
                    "candidate_name": "Julia Roberts",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "MD_009",
                    "candidate_name": "Matt Damon",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "LN_048",
                    "candidate_name": "Lupita Nyong'o",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "FP_038",
                    "candidate_name": "Florence Pugh",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "OS_028",
                    "candidate_name": "Octavia Spencer",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "A_044",
                    "candidate_name": "Awkwafina",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "SH_030",
                    "candidate_name": "Salma Hayek",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "CB_025",
                    "candidate_name": "Chadwick Boseman",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "RM_024",
                    "candidate_name": "Rachel McAdams",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "ZS_014",
                    "candidate_name": "Zoe Saldana",
                    "count": 2
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "CT_010",
                    "candidate_name": "Charlize Theron",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "NK_012",
                    "candidate_name": "Nicole Kidman",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "GG_016",
                    "candidate_name": "Gal Gadot",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "JC_045",
                    "candidate_name": "Jessica Chastain",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "JM_027",
                    "candidate_name": "Jason Momoa",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "RG_029",
                    "candidate_name": "Ryan Gosling",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "EB_022",
                    "candidate_name": "Emily Blunt",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "JG_017",
                    "candidate_name": "Jake Gyllenhaal",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "TH_047",
                    "candidate_name": "Tom Holland",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "EG_036",
                    "candidate_name": "Eva Green",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "DC_019",
                    "candidate_name": "Daniel Craig",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "RM_040",
                    "candidate_name": "Rami Malek",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "ATJ_041",
                    "candidate_name": "Anya Taylor-Joy",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "AG_046",
                    "candidate_name": "Andrew Garfield",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "CE_023",
                    "candidate_name": "Chris Evans",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "BC_049",
                    "candidate_name": "Bryan Cranston",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "PP_039",
                    "candidate_name": "Pedro Pascal",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "MR_021",
                    "candidate_name": "Mark Ruffalo",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "HM_050",
                    "candidate_name": "Helen Mirren",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "TS_026",
                    "candidate_name": "Tilda Swinton",
                    "count": 1
                },
                {
                    "position_code": "SENATOR",
                    "candidate_code": "TC_037",
                    "candidate_name": "Timothee Chalamet",
                    "count": 1
                },
                {
                    "position_code": "GOVERNOR-ILN",
                    "candidate_code": "EN_001",
                    "candidate_name": "Edward Norton",
                    "count": 5
                },
                {
                    "position_code": "GOVERNOR-ILN",
                    "candidate_code": "RP_003",
                    "candidate_name": "Rosamund Pike",
                    "count": 1
                },
                {
                    "position_code": "VICE-GOVERNOR-ILN",
                    "candidate_code": "NW_001",
                    "candidate_name": "Naomi Watts",
                    "count": 2
                },
                {
                    "position_code": "VICE-GOVERNOR-ILN",
                    "candidate_code": "MF_002",
                    "candidate_name": "Michael Fassbender",
                    "count": 2
                },
                {
                    "position_code": "VICE-GOVERNOR-ILN",
                    "candidate_code": "DK_003",
                    "candidate_name": "Daniel Kaluuya",
                    "count": 1
                },
                {
                    "position_code": "VICE-GOVERNOR-ILN",
                    "candidate_code": "JH_004",
                    "candidate_name": "Jennifer Hudson",
                    "count": 1
                },
                {
                    "position_code": "BOARD-MEMBER-ILN",
                    "candidate_code": "RW_001",
                    "candidate_name": "Reese Witherspoon",
                    "count": 3
                },
                {
                    "position_code": "BOARD-MEMBER-ILN",
                    "candidate_code": "HB_002",
                    "candidate_name": "Halle Berry",
                    "count": 2
                },
                {
                    "position_code": "BOARD-MEMBER-ILN",
                    "candidate_code": "DP_004",
                    "candidate_name": "Dev Patel",
                    "count": 2
                },
                {
                    "position_code": "BOARD-MEMBER-ILN",
                    "candidate_code": "DJ_006",
                    "candidate_name": "Dakota Johnson",
                    "count": 2
                },
                {
                    "position_code": "BOARD-MEMBER-ILN",
                    "candidate_code": "BDT_005",
                    "candidate_name": "Benicio Del Toro",
                    "count": 1
                },
                {
                    "position_code": "BOARD-MEMBER-ILN",
                    "candidate_code": "RW_003",
                    "candidate_name": "Robin Wright",
                    "count": 1
                },
                {
                    "position_code": "BOARD-MEMBER-ILN",
                    "candidate_code": "AD_007",
                    "candidate_name": "Adam Driver",
                    "count": 1
                },
                {
                    "position_code": "REPRESENTATIVE-ILN-1",
                    "candidate_code": "DC_004",
                    "candidate_name": "Don Cheadle",
                    "count": 3
                },
                {
                    "position_code": "REPRESENTATIVE-ILN-1",
                    "candidate_code": "JF_001",
                    "candidate_name": "Jodie Foster",
                    "count": 2
                },
                {
                    "position_code": "REPRESENTATIVE-ILN-1",
                    "candidate_code": "RW_002",
                    "candidate_name": "Rachel Weisz",
                    "count": 1
                },
                {
                    "position_code": "MAYOR-ILN-CURRIMAO",
                    "candidate_code": "BC_001",
                    "candidate_name": "Billy Crudup",
                    "count": 3
                },
                {
                    "position_code": "MAYOR-ILN-CURRIMAO",
                    "candidate_code": "LJ_002",
                    "candidate_name": "Lily James",
                    "count": 2
                },
                {
                    "position_code": "MAYOR-ILN-CURRIMAO",
                    "candidate_code": "EW_003",
                    "candidate_name": "Emily Watson",
                    "count": 1
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "ER_001",
                    "candidate_name": "Eddie Redmayne",
                    "count": 6
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "SG_002",
                    "candidate_name": "Stephen Graham",
                    "count": 6
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "SR_003",
                    "candidate_name": "Saoirse Ronan",
                    "count": 6
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "MC_004",
                    "candidate_name": "Marion Cotillard",
                    "count": 6
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "MS_005",
                    "candidate_name": "Michael Shannon",
                    "count": 6
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "CE_006",
                    "candidate_name": "Chiwetel Ejiofor",
                    "count": 6
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "GMR_007",
                    "candidate_name": "Gugu Mbatha-Raw",
                    "count": 6
                },
                {
                    "position_code": "COUNCILOR-ILN-CURRIMAO",
                    "candidate_code": "DO_008",
                    "candidate_name": "David Oyelowo",
                    "count": 6
                },
                {
                    "position_code": "REPRESENTATIVE-PARTY-LIST",
                    "candidate_code": "THE_MATRIX_008",
                    "candidate_name": "The Matrix",
                    "count": 1
                },
                {
                    "position_code": "REPRESENTATIVE-PARTY-LIST",
                    "candidate_code": "TENET_040",
                    "candidate_name": "Tenet",
                    "count": 1
                },
                {
                    "position_code": "REPRESENTATIVE-PARTY-LIST",
                    "candidate_code": "LUCA_148",
                    "candidate_name": "Luca",
                    "count": 1
                },
                {
                    "position_code": "REPRESENTATIVE-PARTY-LIST",
                    "candidate_code": "THE_MARTIAN_044",
                    "candidate_name": "The Martian",
                    "count": 1
                },
                {
                    "position_code": "REPRESENTATIVE-PARTY-LIST",
                    "candidate_code": "THE_GREEN_MILE_081",
                    "candidate_name": "The Green Mile",
                    "count": 1
                },
                {
                    "position_code": "VICE-MAYOR-ILN-CURRIMAO",
                    "candidate_code": "JKS_001",
                    "candidate_name": "J.K. Simmons",
                    "count": 2
                },
                {
                    "position_code": "VICE-MAYOR-ILN-CURRIMAO",
                    "candidate_code": "JL_002",
                    "candidate_name": "Jared Leto",
                    "count": 2
                },
                {
                    "position_code": "VICE-MAYOR-ILN-CURRIMAO",
                    "candidate_code": "JF_003",
                    "candidate_name": "Jamie Foxx",
                    "count": 1
                }
            ],
            "signatures": {
                "3": {
                    "id": "uuid-juan",
                    "name": "Juan dela Cruz",
                    "role": "chairperson",
                    "signature": "signature123",
                    "signed_at": "2025-09-24T14:39:39+00:00"
                },
                "4": {
                    "id": "uuid-maria",
                    "name": "Maria Santos",
                    "role": "member",
                    "signature": "signature456",
                    "signed_at": "2025-09-24T14:39:39+00:00"
                },
                "5": {
                    "id": "uuid-pedro",
                    "name": "Pedro Reyes",
                    "role": "member",
                    "signature": "signature789",
                    "signed_at": "2025-09-24T14:39:39+00:00"
                }
            },
            "ballots": [
                {
                    "precinct_code": "CURRIMAO-001",
                    "code": "BAL-000",
                    "votes": [
                        {
                            "position": {
                                "code": "PRESIDENT",
                                "name": "President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "LD_001",
                                    "name": "Leonardo DiCaprio",
                                    "alias": "LD",
                                    "position": {
                                        "code": "PRESIDENT",
                                        "name": "President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-PRESIDENT",
                                "name": "Vice President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "TH_001",
                                    "name": "Tom Hanks",
                                    "alias": "TH",
                                    "position": {
                                        "code": "VICE-PRESIDENT",
                                        "name": "Vice President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "SENATOR",
                                "name": "Senator of the Philippines",
                                "level": "national",
                                "count": 12
                            },
                            "candidates": [
                                {
                                    "code": "JD_001",
                                    "name": "Johnny Depp",
                                    "alias": "JD",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "CT_010",
                                    "name": "Charlize Theron",
                                    "alias": "CT",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "KR_011",
                                    "name": "Keanu Reeves",
                                    "alias": "KR",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "NK_012",
                                    "name": "Nicole Kidman",
                                    "alias": "NK",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "ES_002",
                                    "name": "Emma Stone",
                                    "alias": "ES",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MF_003",
                                    "name": "Morgan Freeman",
                                    "alias": "MF",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "JL_004",
                                    "name": "Jennifer Lawrence",
                                    "alias": "JL",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "CB_005",
                                    "name": "Christian Bale",
                                    "alias": "CB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "SB_006",
                                    "name": "Sandra Bullock",
                                    "alias": "SB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "WS_007",
                                    "name": "Will Smith",
                                    "alias": "WS",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "JR_008",
                                    "name": "Julia Roberts",
                                    "alias": "JR",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MD_009",
                                    "name": "Matt Damon",
                                    "alias": "MD",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "GOVERNOR-ILN",
                                "name": "Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "EN_001",
                                    "name": "Edward Norton",
                                    "alias": "EN",
                                    "position": {
                                        "code": "GOVERNOR-ILN",
                                        "name": "Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-GOVERNOR-ILN",
                                "name": "Vice Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "NW_001",
                                    "name": "Naomi Watts",
                                    "alias": "NW",
                                    "position": {
                                        "code": "VICE-GOVERNOR-ILN",
                                        "name": "Vice Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "BOARD-MEMBER-ILN",
                                "name": "Provincial Board Member \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 2
                            },
                            "candidates": [
                                {
                                    "code": "RW_001",
                                    "name": "Reese Witherspoon",
                                    "alias": "RW",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                },
                                {
                                    "code": "HB_002",
                                    "name": "Halle Berry",
                                    "alias": "HB",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-ILN-1",
                                "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                "level": "district",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JF_001",
                                    "name": "Jodie Foster",
                                    "alias": "JF",
                                    "position": {
                                        "code": "REPRESENTATIVE-ILN-1",
                                        "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                        "level": "district",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "BC_001",
                                    "name": "Billy Crudup",
                                    "alias": "BC",
                                    "position": {
                                        "code": "MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "COUNCILOR-ILN-CURRIMAO",
                                "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 8
                            },
                            "candidates": [
                                {
                                    "code": "ER_001",
                                    "name": "Eddie Redmayne",
                                    "alias": "ER",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SG_002",
                                    "name": "Stephen Graham",
                                    "alias": "SG",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SR_003",
                                    "name": "Saoirse Ronan",
                                    "alias": "SR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MC_004",
                                    "name": "Marion Cotillard",
                                    "alias": "MC",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MS_005",
                                    "name": "Michael Shannon",
                                    "alias": "MS",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "CE_006",
                                    "name": "Chiwetel Ejiofor",
                                    "alias": "CE",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "GMR_007",
                                    "name": "Gugu Mbatha-Raw",
                                    "alias": "GMR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "DO_008",
                                    "name": "David Oyelowo",
                                    "alias": "DO",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                }
                            ]
                        }
                    ]
                },
                {
                    "precinct_code": "CURRIMAO-001",
                    "code": "BAL-001",
                    "votes": [
                        {
                            "position": {
                                "code": "PRESIDENT",
                                "name": "President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "AJ_006",
                                    "name": "Angelina Jolie",
                                    "alias": "AJ",
                                    "position": {
                                        "code": "PRESIDENT",
                                        "name": "President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-PRESIDENT",
                                "name": "Vice President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "TH_001",
                                    "name": "Tom Hanks",
                                    "alias": "TH",
                                    "position": {
                                        "code": "VICE-PRESIDENT",
                                        "name": "Vice President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "SENATOR",
                                "name": "Senator of the Philippines",
                                "level": "national",
                                "count": 12
                            },
                            "candidates": [
                                {
                                    "code": "ES_002",
                                    "name": "Emma Stone",
                                    "alias": "ES",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "LN_048",
                                    "name": "Lupita Nyong'o",
                                    "alias": "LN",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "AA_018",
                                    "name": "Amy Adams",
                                    "alias": "AA",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "GG_016",
                                    "name": "Gal Gadot",
                                    "alias": "GG",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "BC_015",
                                    "name": "Benedict Cumberbatch",
                                    "alias": "BC",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MD_009",
                                    "name": "Matt Damon",
                                    "alias": "MD",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "WS_007",
                                    "name": "Will Smith",
                                    "alias": "WS",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MA_035",
                                    "name": "Mahershala Ali",
                                    "alias": "MA",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "SB_006",
                                    "name": "Sandra Bullock",
                                    "alias": "SB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "FP_038",
                                    "name": "Florence Pugh",
                                    "alias": "FP",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "OS_028",
                                    "name": "Octavia Spencer",
                                    "alias": "OS",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MF_003",
                                    "name": "Morgan Freeman",
                                    "alias": "MF",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-PARTY-LIST",
                                "name": "Party-list Representative",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "THE_MATRIX_008",
                                    "name": "The Matrix",
                                    "alias": "the_matrix",
                                    "position": {
                                        "code": "REPRESENTATIVE-PARTY-LIST",
                                        "name": "Party-list Representative",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "GOVERNOR-ILN",
                                "name": "Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "EN_001",
                                    "name": "Edward Norton",
                                    "alias": "EN",
                                    "position": {
                                        "code": "GOVERNOR-ILN",
                                        "name": "Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-GOVERNOR-ILN",
                                "name": "Vice Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "MF_002",
                                    "name": "Michael Fassbender",
                                    "alias": "MF",
                                    "position": {
                                        "code": "VICE-GOVERNOR-ILN",
                                        "name": "Vice Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "BOARD-MEMBER-ILN",
                                "name": "Provincial Board Member \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 2
                            },
                            "candidates": [
                                {
                                    "code": "DP_004",
                                    "name": "Dev Patel",
                                    "alias": "DP",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                },
                                {
                                    "code": "BDT_005",
                                    "name": "Benicio Del Toro",
                                    "alias": "BDT",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-ILN-1",
                                "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                "level": "district",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JF_001",
                                    "name": "Jodie Foster",
                                    "alias": "JF",
                                    "position": {
                                        "code": "REPRESENTATIVE-ILN-1",
                                        "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                        "level": "district",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "EW_003",
                                    "name": "Emily Watson",
                                    "alias": "EW",
                                    "position": {
                                        "code": "MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JKS_001",
                                    "name": "J.K. Simmons",
                                    "alias": "JKS",
                                    "position": {
                                        "code": "VICE-MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "COUNCILOR-ILN-CURRIMAO",
                                "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 8
                            },
                            "candidates": [
                                {
                                    "code": "ER_001",
                                    "name": "Eddie Redmayne",
                                    "alias": "ER",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SG_002",
                                    "name": "Stephen Graham",
                                    "alias": "SG",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SR_003",
                                    "name": "Saoirse Ronan",
                                    "alias": "SR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MC_004",
                                    "name": "Marion Cotillard",
                                    "alias": "MC",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MS_005",
                                    "name": "Michael Shannon",
                                    "alias": "MS",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "CE_006",
                                    "name": "Chiwetel Ejiofor",
                                    "alias": "CE",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "GMR_007",
                                    "name": "Gugu Mbatha-Raw",
                                    "alias": "GMR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "DO_008",
                                    "name": "David Oyelowo",
                                    "alias": "DO",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                }
                            ]
                        }
                    ]
                },
                {
                    "precinct_code": "CURRIMAO-001",
                    "code": "BAL-002",
                    "votes": [
                        {
                            "position": {
                                "code": "PRESIDENT",
                                "name": "President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "AJ_006",
                                    "name": "Angelina Jolie",
                                    "alias": "AJ",
                                    "position": {
                                        "code": "PRESIDENT",
                                        "name": "President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-PRESIDENT",
                                "name": "Vice President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "AH_006",
                                    "name": "Anne Hathaway",
                                    "alias": "AH",
                                    "position": {
                                        "code": "VICE-PRESIDENT",
                                        "name": "Vice President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "SENATOR",
                                "name": "Senator of the Philippines",
                                "level": "national",
                                "count": 12
                            },
                            "candidates": [
                                {
                                    "code": "JC_045",
                                    "name": "Jessica Chastain",
                                    "alias": "JC",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MA_035",
                                    "name": "Mahershala Ali",
                                    "alias": "MA",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "JM_027",
                                    "name": "Jason Momoa",
                                    "alias": "JM",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "BC_015",
                                    "name": "Benedict Cumberbatch",
                                    "alias": "BC",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "RG_029",
                                    "name": "Ryan Gosling",
                                    "alias": "RG",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "FP_038",
                                    "name": "Florence Pugh",
                                    "alias": "FP",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "AA_018",
                                    "name": "Amy Adams",
                                    "alias": "AA",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "JD_001",
                                    "name": "Johnny Depp",
                                    "alias": "JD",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "KR_011",
                                    "name": "Keanu Reeves",
                                    "alias": "KR",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "OS_028",
                                    "name": "Octavia Spencer",
                                    "alias": "OS",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "EB_022",
                                    "name": "Emily Blunt",
                                    "alias": "EB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "A_044",
                                    "name": "Awkwafina",
                                    "alias": "A",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-PARTY-LIST",
                                "name": "Party-list Representative",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "TENET_040",
                                    "name": "Tenet",
                                    "alias": "tenet",
                                    "position": {
                                        "code": "REPRESENTATIVE-PARTY-LIST",
                                        "name": "Party-list Representative",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "GOVERNOR-ILN",
                                "name": "Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "EN_001",
                                    "name": "Edward Norton",
                                    "alias": "EN",
                                    "position": {
                                        "code": "GOVERNOR-ILN",
                                        "name": "Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-GOVERNOR-ILN",
                                "name": "Vice Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "DK_003",
                                    "name": "Daniel Kaluuya",
                                    "alias": "DK",
                                    "position": {
                                        "code": "VICE-GOVERNOR-ILN",
                                        "name": "Vice Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "BOARD-MEMBER-ILN",
                                "name": "Provincial Board Member \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 2
                            },
                            "candidates": [
                                {
                                    "code": "HB_002",
                                    "name": "Halle Berry",
                                    "alias": "HB",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                },
                                {
                                    "code": "RW_001",
                                    "name": "Reese Witherspoon",
                                    "alias": "RW",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-ILN-1",
                                "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                "level": "district",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "DC_004",
                                    "name": "Don Cheadle",
                                    "alias": "DC",
                                    "position": {
                                        "code": "REPRESENTATIVE-ILN-1",
                                        "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                        "level": "district",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "BC_001",
                                    "name": "Billy Crudup",
                                    "alias": "BC",
                                    "position": {
                                        "code": "MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JL_002",
                                    "name": "Jared Leto",
                                    "alias": "JL",
                                    "position": {
                                        "code": "VICE-MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "COUNCILOR-ILN-CURRIMAO",
                                "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 8
                            },
                            "candidates": [
                                {
                                    "code": "ER_001",
                                    "name": "Eddie Redmayne",
                                    "alias": "ER",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SG_002",
                                    "name": "Stephen Graham",
                                    "alias": "SG",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SR_003",
                                    "name": "Saoirse Ronan",
                                    "alias": "SR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MC_004",
                                    "name": "Marion Cotillard",
                                    "alias": "MC",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MS_005",
                                    "name": "Michael Shannon",
                                    "alias": "MS",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "CE_006",
                                    "name": "Chiwetel Ejiofor",
                                    "alias": "CE",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "GMR_007",
                                    "name": "Gugu Mbatha-Raw",
                                    "alias": "GMR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "DO_008",
                                    "name": "David Oyelowo",
                                    "alias": "DO",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                }
                            ]
                        }
                    ]
                },
                {
                    "precinct_code": "CURRIMAO-001",
                    "code": "BAL-003",
                    "votes": [
                        {
                            "position": {
                                "code": "PRESIDENT",
                                "name": "President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "DW_003",
                                    "name": "Denzel Washington",
                                    "alias": "DW",
                                    "position": {
                                        "code": "PRESIDENT",
                                        "name": "President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-PRESIDENT",
                                "name": "Vice President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "RDJ_005",
                                    "name": "Robert Downey Jr.",
                                    "alias": "RDJ",
                                    "position": {
                                        "code": "VICE-PRESIDENT",
                                        "name": "Vice President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "SENATOR",
                                "name": "Senator of the Philippines",
                                "level": "national",
                                "count": 12
                            },
                            "candidates": [
                                {
                                    "code": "JG_017",
                                    "name": "Jake Gyllenhaal",
                                    "alias": "JG",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MF_003",
                                    "name": "Morgan Freeman",
                                    "alias": "MF",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "TH_047",
                                    "name": "Tom Holland",
                                    "alias": "TH",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "SH_030",
                                    "name": "Salma Hayek",
                                    "alias": "SH",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MA_035",
                                    "name": "Mahershala Ali",
                                    "alias": "MA",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "JR_008",
                                    "name": "Julia Roberts",
                                    "alias": "JR",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "CB_025",
                                    "name": "Chadwick Boseman",
                                    "alias": "CB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "SB_006",
                                    "name": "Sandra Bullock",
                                    "alias": "SB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "EG_036",
                                    "name": "Eva Green",
                                    "alias": "EG",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "DC_019",
                                    "name": "Daniel Craig",
                                    "alias": "DC",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "RM_040",
                                    "name": "Rami Malek",
                                    "alias": "RM",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "RM_024",
                                    "name": "Rachel McAdams",
                                    "alias": "RM",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-PARTY-LIST",
                                "name": "Party-list Representative",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "LUCA_148",
                                    "name": "Luca",
                                    "alias": "luca",
                                    "position": {
                                        "code": "REPRESENTATIVE-PARTY-LIST",
                                        "name": "Party-list Representative",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "GOVERNOR-ILN",
                                "name": "Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "EN_001",
                                    "name": "Edward Norton",
                                    "alias": "EN",
                                    "position": {
                                        "code": "GOVERNOR-ILN",
                                        "name": "Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-GOVERNOR-ILN",
                                "name": "Vice Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "NW_001",
                                    "name": "Naomi Watts",
                                    "alias": "NW",
                                    "position": {
                                        "code": "VICE-GOVERNOR-ILN",
                                        "name": "Vice Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "BOARD-MEMBER-ILN",
                                "name": "Provincial Board Member \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 2
                            },
                            "candidates": [
                                {
                                    "code": "RW_001",
                                    "name": "Reese Witherspoon",
                                    "alias": "RW",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                },
                                {
                                    "code": "DJ_006",
                                    "name": "Dakota Johnson",
                                    "alias": "DJ",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-ILN-1",
                                "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                "level": "district",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "RW_002",
                                    "name": "Rachel Weisz",
                                    "alias": "RW",
                                    "position": {
                                        "code": "REPRESENTATIVE-ILN-1",
                                        "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                        "level": "district",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "LJ_002",
                                    "name": "Lily James",
                                    "alias": "LJ",
                                    "position": {
                                        "code": "MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JKS_001",
                                    "name": "J.K. Simmons",
                                    "alias": "JKS",
                                    "position": {
                                        "code": "VICE-MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "COUNCILOR-ILN-CURRIMAO",
                                "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 8
                            },
                            "candidates": [
                                {
                                    "code": "ER_001",
                                    "name": "Eddie Redmayne",
                                    "alias": "ER",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SG_002",
                                    "name": "Stephen Graham",
                                    "alias": "SG",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SR_003",
                                    "name": "Saoirse Ronan",
                                    "alias": "SR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MC_004",
                                    "name": "Marion Cotillard",
                                    "alias": "MC",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MS_005",
                                    "name": "Michael Shannon",
                                    "alias": "MS",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "CE_006",
                                    "name": "Chiwetel Ejiofor",
                                    "alias": "CE",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "GMR_007",
                                    "name": "Gugu Mbatha-Raw",
                                    "alias": "GMR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "DO_008",
                                    "name": "David Oyelowo",
                                    "alias": "DO",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                }
                            ]
                        }
                    ]
                },
                {
                    "precinct_code": "CURRIMAO-001",
                    "code": "BAL-004",
                    "votes": [
                        {
                            "position": {
                                "code": "PRESIDENT",
                                "name": "President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "SJ_002",
                                    "name": "Scarlett Johansson",
                                    "alias": "SJ",
                                    "position": {
                                        "code": "PRESIDENT",
                                        "name": "President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-PRESIDENT",
                                "name": "Vice President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "TH_001",
                                    "name": "Tom Hanks",
                                    "alias": "TH",
                                    "position": {
                                        "code": "VICE-PRESIDENT",
                                        "name": "Vice President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "SENATOR",
                                "name": "Senator of the Philippines",
                                "level": "national",
                                "count": 12
                            },
                            "candidates": [
                                {
                                    "code": "CB_025",
                                    "name": "Chadwick Boseman",
                                    "alias": "CB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "AA_018",
                                    "name": "Amy Adams",
                                    "alias": "AA",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "SH_030",
                                    "name": "Salma Hayek",
                                    "alias": "SH",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "ATJ_041",
                                    "name": "Anya Taylor-Joy",
                                    "alias": "ATJ",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "RM_024",
                                    "name": "Rachel McAdams",
                                    "alias": "RM",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "KR_011",
                                    "name": "Keanu Reeves",
                                    "alias": "KR",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "AG_046",
                                    "name": "Andrew Garfield",
                                    "alias": "AG",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "CE_023",
                                    "name": "Chris Evans",
                                    "alias": "CE",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "ZS_014",
                                    "name": "Zoe Saldana",
                                    "alias": "ZS",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "BC_049",
                                    "name": "Bryan Cranston",
                                    "alias": "BC",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "CB_005",
                                    "name": "Christian Bale",
                                    "alias": "CB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "PP_039",
                                    "name": "Pedro Pascal",
                                    "alias": "PP",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-PARTY-LIST",
                                "name": "Party-list Representative",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "THE_MARTIAN_044",
                                    "name": "The Martian",
                                    "alias": "the_martian",
                                    "position": {
                                        "code": "REPRESENTATIVE-PARTY-LIST",
                                        "name": "Party-list Representative",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "GOVERNOR-ILN",
                                "name": "Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "RP_003",
                                    "name": "Rosamund Pike",
                                    "alias": "RP",
                                    "position": {
                                        "code": "GOVERNOR-ILN",
                                        "name": "Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-GOVERNOR-ILN",
                                "name": "Vice Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "MF_002",
                                    "name": "Michael Fassbender",
                                    "alias": "MF",
                                    "position": {
                                        "code": "VICE-GOVERNOR-ILN",
                                        "name": "Vice Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "BOARD-MEMBER-ILN",
                                "name": "Provincial Board Member \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 2
                            },
                            "candidates": [
                                {
                                    "code": "RW_003",
                                    "name": "Robin Wright",
                                    "alias": "RW",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                },
                                {
                                    "code": "DP_004",
                                    "name": "Dev Patel",
                                    "alias": "DP",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-ILN-1",
                                "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                "level": "district",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "DC_004",
                                    "name": "Don Cheadle",
                                    "alias": "DC",
                                    "position": {
                                        "code": "REPRESENTATIVE-ILN-1",
                                        "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                        "level": "district",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "LJ_002",
                                    "name": "Lily James",
                                    "alias": "LJ",
                                    "position": {
                                        "code": "MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JF_003",
                                    "name": "Jamie Foxx",
                                    "alias": "JF",
                                    "position": {
                                        "code": "VICE-MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "COUNCILOR-ILN-CURRIMAO",
                                "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 8
                            },
                            "candidates": [
                                {
                                    "code": "ER_001",
                                    "name": "Eddie Redmayne",
                                    "alias": "ER",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SG_002",
                                    "name": "Stephen Graham",
                                    "alias": "SG",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SR_003",
                                    "name": "Saoirse Ronan",
                                    "alias": "SR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MC_004",
                                    "name": "Marion Cotillard",
                                    "alias": "MC",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MS_005",
                                    "name": "Michael Shannon",
                                    "alias": "MS",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "CE_006",
                                    "name": "Chiwetel Ejiofor",
                                    "alias": "CE",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "GMR_007",
                                    "name": "Gugu Mbatha-Raw",
                                    "alias": "GMR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "DO_008",
                                    "name": "David Oyelowo",
                                    "alias": "DO",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                }
                            ]
                        }
                    ]
                },
                {
                    "precinct_code": "CURRIMAO-001",
                    "code": "BAL-005",
                    "votes": [
                        {
                            "position": {
                                "code": "PRESIDENT",
                                "name": "President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "AJ_006",
                                    "name": "Angelina Jolie",
                                    "alias": "AJ",
                                    "position": {
                                        "code": "PRESIDENT",
                                        "name": "President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-PRESIDENT",
                                "name": "Vice President of the Philippines",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "RDJ_005",
                                    "name": "Robert Downey Jr.",
                                    "alias": "RDJ",
                                    "position": {
                                        "code": "VICE-PRESIDENT",
                                        "name": "Vice President of the Philippines",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "SENATOR",
                                "name": "Senator of the Philippines",
                                "level": "national",
                                "count": 12
                            },
                            "candidates": [
                                {
                                    "code": "BC_015",
                                    "name": "Benedict Cumberbatch",
                                    "alias": "BC",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "A_044",
                                    "name": "Awkwafina",
                                    "alias": "A",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MR_021",
                                    "name": "Mark Ruffalo",
                                    "alias": "MR",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "JL_004",
                                    "name": "Jennifer Lawrence",
                                    "alias": "JL",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "HM_050",
                                    "name": "Helen Mirren",
                                    "alias": "HM",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "MF_003",
                                    "name": "Morgan Freeman",
                                    "alias": "MF",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "LN_048",
                                    "name": "Lupita Nyong'o",
                                    "alias": "LN",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "TS_026",
                                    "name": "Tilda Swinton",
                                    "alias": "TS",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "AA_018",
                                    "name": "Amy Adams",
                                    "alias": "AA",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "CB_005",
                                    "name": "Christian Bale",
                                    "alias": "CB",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "ZS_014",
                                    "name": "Zoe Saldana",
                                    "alias": "ZS",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                },
                                {
                                    "code": "TC_037",
                                    "name": "Timothee Chalamet",
                                    "alias": "TC",
                                    "position": {
                                        "code": "SENATOR",
                                        "name": "Senator of the Philippines",
                                        "level": "national",
                                        "count": 12
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-PARTY-LIST",
                                "name": "Party-list Representative",
                                "level": "national",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "THE_GREEN_MILE_081",
                                    "name": "The Green Mile",
                                    "alias": "the_green_mile",
                                    "position": {
                                        "code": "REPRESENTATIVE-PARTY-LIST",
                                        "name": "Party-list Representative",
                                        "level": "national",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "GOVERNOR-ILN",
                                "name": "Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "EN_001",
                                    "name": "Edward Norton",
                                    "alias": "EN",
                                    "position": {
                                        "code": "GOVERNOR-ILN",
                                        "name": "Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-GOVERNOR-ILN",
                                "name": "Vice Governor \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JH_004",
                                    "name": "Jennifer Hudson",
                                    "alias": "JH",
                                    "position": {
                                        "code": "VICE-GOVERNOR-ILN",
                                        "name": "Vice Governor \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "BOARD-MEMBER-ILN",
                                "name": "Provincial Board Member \u2014 Ilocos Norte",
                                "level": "local",
                                "count": 2
                            },
                            "candidates": [
                                {
                                    "code": "AD_007",
                                    "name": "Adam Driver",
                                    "alias": "AD",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                },
                                {
                                    "code": "DJ_006",
                                    "name": "Dakota Johnson",
                                    "alias": "DJ",
                                    "position": {
                                        "code": "BOARD-MEMBER-ILN",
                                        "name": "Provincial Board Member \u2014 Ilocos Norte",
                                        "level": "local",
                                        "count": 2
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "REPRESENTATIVE-ILN-1",
                                "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                "level": "district",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "DC_004",
                                    "name": "Don Cheadle",
                                    "alias": "DC",
                                    "position": {
                                        "code": "REPRESENTATIVE-ILN-1",
                                        "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                        "level": "district",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "BC_001",
                                    "name": "Billy Crudup",
                                    "alias": "BC",
                                    "position": {
                                        "code": "MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "VICE-MAYOR-ILN-CURRIMAO",
                                "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 1
                            },
                            "candidates": [
                                {
                                    "code": "JL_002",
                                    "name": "Jared Leto",
                                    "alias": "JL",
                                    "position": {
                                        "code": "VICE-MAYOR-ILN-CURRIMAO",
                                        "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 1
                                    }
                                }
                            ]
                        },
                        {
                            "position": {
                                "code": "COUNCILOR-ILN-CURRIMAO",
                                "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                "level": "local",
                                "count": 8
                            },
                            "candidates": [
                                {
                                    "code": "ER_001",
                                    "name": "Eddie Redmayne",
                                    "alias": "ER",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SG_002",
                                    "name": "Stephen Graham",
                                    "alias": "SG",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "SR_003",
                                    "name": "Saoirse Ronan",
                                    "alias": "SR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MC_004",
                                    "name": "Marion Cotillard",
                                    "alias": "MC",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "MS_005",
                                    "name": "Michael Shannon",
                                    "alias": "MS",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "CE_006",
                                    "name": "Chiwetel Ejiofor",
                                    "alias": "CE",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "GMR_007",
                                    "name": "Gugu Mbatha-Raw",
                                    "alias": "GMR",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                },
                                {
                                    "code": "DO_008",
                                    "name": "David Oyelowo",
                                    "alias": "DO",
                                    "position": {
                                        "code": "COUNCILOR-ILN-CURRIMAO",
                                        "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                        "level": "local",
                                        "count": 8
                                    }
                                }
                            ]
                        }
                    ]
                }
            ],
            "created_at": "2025-09-24T14:39:38+00:00",
            "updated_at": "2025-09-24T14:39:39+00:00",
            "last_ballot": {
                "precinct_code": "CURRIMAO-001",
                "code": "BAL-005",
                "votes": [
                    {
                        "position": {
                            "code": "PRESIDENT",
                            "name": "President of the Philippines",
                            "level": "national",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "AJ_006",
                                "name": "Angelina Jolie",
                                "alias": "AJ",
                                "position": {
                                    "code": "PRESIDENT",
                                    "name": "President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "VICE-PRESIDENT",
                            "name": "Vice President of the Philippines",
                            "level": "national",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "RDJ_005",
                                "name": "Robert Downey Jr.",
                                "alias": "RDJ",
                                "position": {
                                    "code": "VICE-PRESIDENT",
                                    "name": "Vice President of the Philippines",
                                    "level": "national",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "SENATOR",
                            "name": "Senator of the Philippines",
                            "level": "national",
                            "count": 12
                        },
                        "candidates": [
                            {
                                "code": "BC_015",
                                "name": "Benedict Cumberbatch",
                                "alias": "BC",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "A_044",
                                "name": "Awkwafina",
                                "alias": "A",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "MR_021",
                                "name": "Mark Ruffalo",
                                "alias": "MR",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "JL_004",
                                "name": "Jennifer Lawrence",
                                "alias": "JL",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "HM_050",
                                "name": "Helen Mirren",
                                "alias": "HM",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "MF_003",
                                "name": "Morgan Freeman",
                                "alias": "MF",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "LN_048",
                                "name": "Lupita Nyong'o",
                                "alias": "LN",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "TS_026",
                                "name": "Tilda Swinton",
                                "alias": "TS",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "AA_018",
                                "name": "Amy Adams",
                                "alias": "AA",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "CB_005",
                                "name": "Christian Bale",
                                "alias": "CB",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "ZS_014",
                                "name": "Zoe Saldana",
                                "alias": "ZS",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            },
                            {
                                "code": "TC_037",
                                "name": "Timothee Chalamet",
                                "alias": "TC",
                                "position": {
                                    "code": "SENATOR",
                                    "name": "Senator of the Philippines",
                                    "level": "national",
                                    "count": 12
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "REPRESENTATIVE-PARTY-LIST",
                            "name": "Party-list Representative",
                            "level": "national",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "THE_GREEN_MILE_081",
                                "name": "The Green Mile",
                                "alias": "the_green_mile",
                                "position": {
                                    "code": "REPRESENTATIVE-PARTY-LIST",
                                    "name": "Party-list Representative",
                                    "level": "national",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "GOVERNOR-ILN",
                            "name": "Governor \u2014 Ilocos Norte",
                            "level": "local",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "EN_001",
                                "name": "Edward Norton",
                                "alias": "EN",
                                "position": {
                                    "code": "GOVERNOR-ILN",
                                    "name": "Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "VICE-GOVERNOR-ILN",
                            "name": "Vice Governor \u2014 Ilocos Norte",
                            "level": "local",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "JH_004",
                                "name": "Jennifer Hudson",
                                "alias": "JH",
                                "position": {
                                    "code": "VICE-GOVERNOR-ILN",
                                    "name": "Vice Governor \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "BOARD-MEMBER-ILN",
                            "name": "Provincial Board Member \u2014 Ilocos Norte",
                            "level": "local",
                            "count": 2
                        },
                        "candidates": [
                            {
                                "code": "AD_007",
                                "name": "Adam Driver",
                                "alias": "AD",
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                }
                            },
                            {
                                "code": "DJ_006",
                                "name": "Dakota Johnson",
                                "alias": "DJ",
                                "position": {
                                    "code": "BOARD-MEMBER-ILN",
                                    "name": "Provincial Board Member \u2014 Ilocos Norte",
                                    "level": "local",
                                    "count": 2
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "REPRESENTATIVE-ILN-1",
                            "name": "District Representative \u2014 Ilocos Norte (1st District)",
                            "level": "district",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "DC_004",
                                "name": "Don Cheadle",
                                "alias": "DC",
                                "position": {
                                    "code": "REPRESENTATIVE-ILN-1",
                                    "name": "District Representative \u2014 Ilocos Norte (1st District)",
                                    "level": "district",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "MAYOR-ILN-CURRIMAO",
                            "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                            "level": "local",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "BC_001",
                                "name": "Billy Crudup",
                                "alias": "BC",
                                "position": {
                                    "code": "MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "VICE-MAYOR-ILN-CURRIMAO",
                            "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                            "level": "local",
                            "count": 1
                        },
                        "candidates": [
                            {
                                "code": "JL_002",
                                "name": "Jared Leto",
                                "alias": "JL",
                                "position": {
                                    "code": "VICE-MAYOR-ILN-CURRIMAO",
                                    "name": "Municipal Vice Mayor \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 1
                                }
                            }
                        ]
                    },
                    {
                        "position": {
                            "code": "COUNCILOR-ILN-CURRIMAO",
                            "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                            "level": "local",
                            "count": 8
                        },
                        "candidates": [
                            {
                                "code": "ER_001",
                                "name": "Eddie Redmayne",
                                "alias": "ER",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            },
                            {
                                "code": "SG_002",
                                "name": "Stephen Graham",
                                "alias": "SG",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            },
                            {
                                "code": "SR_003",
                                "name": "Saoirse Ronan",
                                "alias": "SR",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            },
                            {
                                "code": "MC_004",
                                "name": "Marion Cotillard",
                                "alias": "MC",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            },
                            {
                                "code": "MS_005",
                                "name": "Michael Shannon",
                                "alias": "MS",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            },
                            {
                                "code": "CE_006",
                                "name": "Chiwetel Ejiofor",
                                "alias": "CE",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            },
                            {
                                "code": "GMR_007",
                                "name": "Gugu Mbatha-Raw",
                                "alias": "GMR",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            },
                            {
                                "code": "DO_008",
                                "name": "David Oyelowo",
                                "alias": "DO",
                                "position": {
                                    "code": "COUNCILOR-ILN-CURRIMAO",
                                    "name": "Sangguniang Bayan Member \u2014 Currimao, Ilocos Norte",
                                    "level": "local",
                                    "count": 8
                                }
                            }
                        ]
                    }
                ]
            }
        },
        "qrMeta": {
            "code": "317537",
            "by": "size",
            "lines": [
                "ER|v1|317537|1/6|7Z1bc-LIkoD_SoVfdjdOe4Kb25c3QDIYI0wIzXjO7HYQ1aja1LFQOSRhNz0x_31T2IjUzRc1I2tMRpyYYxpQJqlSVlZ-lVl_HnzljqMC_-Dsf_88mClbHJwddNrDw1qtdvDp4M4TM-nOgunTO91fTfPCaF_B23V4-14F4umb3LWlzTcvuSM5_HUw1OBTT98datPHb7l8sX4tlMs9WzFNdvmdJ1UoT_kykMo9OIuUGZv65ELTR9b6Sks3ODirfzpwxL1w4F2Xhx_nzvayY0_40hZuwNQ3FswFG8-lI-_upAu6_fXXlzKEfMo1iNXfGsTqxw1iqQXrc_fWzzbEbxdd_fCtiv4mZ6BcEZP8beLyjTNAo2WQGC0DNXfdFdPE3V22eSb6qG1dmUjRxnOaTgT8m_JyVdzo1LW2OnWtaa1e216jO-eeI38IZs2FB8qUqdeludXr0gS9kK0uBXeXzBQgxC9VqdHlVqnRJSjV2F5jJGfKEexS2gterqn0yVYrfQLDCmmlLxacTQLlilJVMs63KhnnoFJzew1DeTfcZeeeEGVbajBED-AQ1GqhB1C4rvwmPDbkD55wZ-UarNtBj2EHNDvCj6En_UCCzTrcKVetCVJrEqr1GV0DnJzHWWcJ8-vstlS1rtGIvw5H_PH2GtfScdhkIYN5uUML-asB-KvaCRpaS_gQM9VX4QXlOiwDzThGOOOcoueQBwHT-KIMx_6ljB-dM-vqI-QeR_FZV7cfIERjI-UFeWboXf2mm6Mr8_BiOMqMEmD0Yy176l54Lqj5f8tGrd5iF_C-8tciRK4ldiwk3xijazSDXceNMeJqIdk1DIvnIrS3qroOmQoZ5W8Tl28eE5nHTJgH4g1fsGtwLMLz71TeeOlctU3t0NCNjp5QupGr9NhT97AMkdxhHRUOSEMswFnkq7_Rt4_8c78Tn_r7sPARrCM8b_Vemn55PwvlheAoMBmcJ0NwWwp2rvxAeNkWM_VwwQDLhbZ18Zse6n5YzxyUNkzYnpwF24trT_8CYSssOn1YPYB_uxdZP4D9d90Hz_z0hf_JteN7aZNv3U53a91ON27dDszLK9b1lvYyZ4VjtP_9-Jwfbpbhr3ngjaUrZ_IOBobBV9tHvrv0PLng6tPrxmQ5sp-ZpFD4oJvJSSocl6aAhcUqL47vXv066l4Mc3_DSe5vgDDu5gZ-B_wfxJcr",
                "ER|v1|317537|2/6|iDLjj9ZzPyYKE3soTOzF3dAkEHdz4bKex-d8UUntke0nZnyxMuFKeuD2TQhF3Coqb6BHzujGlzQG90BV1lUBPHvgNCupPwrljUl84WPI2ZwLiObn3HVVJc3f1dG6TY8vkLpz-SAC0F__j1TflFdF_XsGGvzwIr6W6i1vlsz4yoM5PzT5QxV_gHa11V-7iq-7NH4vbXa1Eo56UJVR_ktlFFnPRzglXt9BSrw92N6Q9iD-RLTdG-FIl7OBcqSgfPie58MrmLgcomTBcDSttZA7GS7vZMDZaKXcm_9SparVbqOHqj2t1ZFa7cWKtW2-KDez1EMhXw9Cvjp6zHsQEPe4rYJSNUouPuookugIV9jheqe7DF0jzGiz-V4m4qqdRDXQMDdgmDdxMMjDvMucO5y1HUlZ8LOD8_FWrfMxWAs5hXNHrSEGGy9vyr2HV2hYXcGwaiCtrmYBhGTg1-9C3by9RVLvmQ6Hz04XPPDkdxyZ6FOjbZkXv8cDaAuua2w--3IGatw2rX8fDi8mrw6ruBesDh3pJxNRr8157VQiIYRXpapSD1IjvWI_577_Vbh23jP-8UmChpyzNo7nZTRxz8bwDacCBKGjoQ0g8CKegYG4Sc6kYhrcU0t5ikACgYQyQIKOOJx-HZ-s9YV0ViGm9PP87T6DhMElir_gRWJk_nL5C5vIBaw1nss6_OyPWLvOn7Fi2VoQmCEwQ2CGwAyBGQIzVQQzDQIzOwYzbQRm2v2kNVzB-uHD9cBXe1usgOavAcxfrSO8V9r35Yyz7pz7AZcuJYnBJgaylzGtNZC7HnBYqzBDLRQnJBLttUSxqQmxaQMhETP0lj3lg0-6oZx6BfFfFUuZKlkyVFUcoiOmpXdAr0Yyw9JxQnGlDnM0ymHCQQum9sPtA_8GAcoHZzPgkwOEZfSRboEhUEme9fQJgjEEY9arLFSRqF3GUyQadyUsci-5s1yu-L6CmIqXSHz6hxWfVI_EaCjI1RKJNi3Mss0Ft_PqJgnEUEXH3wRikrXGDbwe9ITNhiJQBGEIwhCEIQhDEIYgDEGYakOY5g4gjIZifC2xsUUT7g94Hq65P5fuTe4C9mNxGFNDWApexH3cY88MpqkHV6zYwPtlb2kMiiEGYblFjC7cwvp4BUtZd865Q41-0mVXreNE2ZWCKd-1y60iQDpNQKdmDXspZ8FZn6_ELbG0qjbT",
                "ER|v1|317537|3/6|SfZoasR6NHH7Qc5uWUf5pY_2itan6Mhn6eCzmkgt_Z7DmkeIcg2VTBXVT1PZ0q7HZbnI0UTg2DTi6X6TLySsnp2S3UJSpUYLqzSbg52MWUnQ8T15jLOccVQP-mu3Pa3HK0JnnGAMwRhqrvURm2vhlYmW2DCn8VsVhNvl5q5PMOaZe9xIzRzXQvo_iMYUoTFDNCKHg7h1h-GejQH87ROLoaIY4jHEY4jHEI8hHkM85qPymNYOeMwERVSTREQ1mXHPEUEQRvnc9f39ADLUsewZ41Q2BVy9CoEq4o62hWvgLHjeW3Vc9rXizOIrR3mHA7Xa24xr1Usq2ih-b0P83oqV7tmeeGA97n2TwikX8CWjq0YzcYYH0--5W66t_kAB6x8QsNbRuPpDCQZPos3d961KayEK0_HCkKDrgZ2CkvvhVfQIljGqSBuHFWnIWmNhe4qNuT8rA_u_f9cyL7wFybZlpnXRHsXLhR77lm0-vcd4xkSDxxzHkwWm8vli6dpsLG8FNS6jxmU5GfzYkPkqXXbtyZt5UAVAU-Uea1QoQ2iG0Mzb--g18cbGxbqP3vfvhGUIyxCWISxDWIawDGGZamOZI-pVRjUy7wFnKt3tqhK9bfCUiVx26LEb9diUf8vM5bdv3FF0PvdjFxHEZ_rGtHaEaFZfOBCaGtLzSi5nqGjxVUXPULJQkGiFnbjQnGJJx-Zs8iDdsqlD9cBtRTlIFUmWhWYcC2acJi4zlAsFXxZhk0wH_in4-IDmJizomi7kemQgRtMzdX00NS6GsLY5qccxzboIDJznK_OOVEizD6RmgPaODPo5cUB_aeduyfr4mKaNGnC2tfiKO5wzmObBkPOojIZQDaEa6mlGPc0I1hCsIVhDsIZgDcGa_YU1XyLTNevHR-t8xcwTMDnZUw4yDxq1xtFh7fSw0bLqrbPm6Vnz5F-12lktTHdKGz5Qq5-eHs8a_PDzV84Pj5ut48MT3jg9PKkdN8XsqFa3G6HXcbgfTL9yx1EBDqkJEBEgIkBEgIgAEQEiAkQEiAgQESAiQESAiAARASICRASICBARICJARICIABEBIgJEBIgIEJVezROhmVATPguW3JmGOMbzp09yj2swBz6ynSdGg-lObQd0Z4jWLsPE6dJDAX7XsxXEcl1-B96MmrLteVO2Kp5F3rXQJGBNa_VarFGc58gfgllz4ZWcTa5k",
                "ER|v1|317537|4/6|Q68ROsR4dAlKoVhxJGfKEexS2mVTCh2FIfokHsHqiwVnk0C5gnhOlalcRVFFRQ_suUYj_noSj_uuYb3AJgsZzOkkqAMDzThGOOOc4hVWEDCNL8pw7O_Jc4hH0MkvH_Xklz7yz_1OfOrvw8JHsI7wvBXhiNwuTrEQ3F53cfKDPNpEPIJ4xN_DIyiHTjl0yqFTDp1y6JRDf7-OWHUqeKB8eHn58AomLiu647t6e6t7KOTrQchXR495DwLiHrdV8K6nNVSqaKYqibhqJ1ENNMwNGOZNHAzyMO8S7kBnbUdSFvzs4Bx1lT8PD9xATuHcUWuIwcbLm3Lv4RUaVldhcQrS6moWQEgGfv0u1M3bWyT1_geUBJ78njyfxDIvfo8H0I_Hkzx9lkoaCCHQ6SSv3vde5SM_cNSkoQ0g8CKegYG4Sc6kYhrcU0t5ikACgYQyQIKOOJyeONtHX4SnlFzzILfOhk6QpxPkqbiBwAyBGQIzBGYIzPzzwUyDwMyOwUwbgZl2P2kNV7B--HA98NXeFiug-WsQHn58hPdK-76c8bAhiB9w6VKSGGyC-ioNwoPRj3FFMaxVmKEWihMSifZaotjUhNi0gZCIGXrLnvLBJ91QTr2C-K-KpUyVLBmqKg7REdPSO6BXI5lh6TihuL3rLPiubAZ8coCwjD7SLTAEKsmznj5BMIZgzHqVhSoStct4ikTjroRF7iV3lssV31cQU_ESiU__sOIT6jBFFR1U0UEdpgjCEIQhCEMQhiAMQZg9gTDNHUAYDcX4WmJjiybcH_A8XHN_Lt2b3AUsnQiyjzQGxRCDsNwiRhduYX28gqWsO-fcoUY_6bKr1nGi7ErBlO_a5VYRIJ0moFOzhr2Us-Csz1fillhaVZvpJHs0NWI9mrj9IGe3rKP80kd7RetTdOSzdPBZTaSWfs8fD0woVaNkqqh-msqWdj0uy0WOJgLHphFP95t8IWH17JTsFpIqNVpYpdkc7GTMSoKO78ljnOWMo3rQX7vtaT1eETrjBGMIxlBzrY_YXIuO-9jBPW6kZo5rIf0fRGOK0JghGpHDQdy6w3DPxgD-9onFUFEM8RjiMcRjiMcQjyEe81F5TGsHPGaCIqpJIqKazLjniCAIo3zu-v5-ABnqWPaMcSqbAq5ehUAVcUfbwjVwFjzvrTou-1pxZvGVo7zDgVrtbca16iUVbRS_tyF-",
                "ER|v1|317537|5/6|b8VK92xPPLAe975J4ZQL-JLRVaOZOMOD6ffc9ff-rPHkntIWojAdLwwJuh7Yqezz6yt6BMsYVaSNw4o0ZK2xsD3FxtyflYH9379rmRfegmTbMtO6aI_i5UKPfcs2n95jPGOiwWOO48kCU_l8sXRtNpa3ghqXUeOynAx-bMh8lS679uTNPKgCoKlyjzUqlCE0Q2jm7X30mnhj42LdR-_7d8IyhGUIyxCWISxDWIawTLWxzBH1KqMamfeAM5XudlWJ3jZ4ykQuO_TYjXpsyr9l5vLbN-4oOp_7sYsI4jN9Y1o7QjSrLxwITQ3peSWXM1S0-KqiZyhZKEi0wk5caE6xpGNzNnmQbtnUoXrgtqIcpIoky0IzjgUzThOXGcqFgi-LsEmmA_8UfHxAcxMWdE0Xcj0yEKPpmbo-mhoXQ1jbnNTjmGZdBAbO85V5Ryqk2QdSM0B7Rwb9nDigv7Rzt2R9fEzTRg0421p8xR3OGUzzYMh5VEZDqIZQDfU0o55mBGsI1hCsIVhDsIZgzf7CGlDlK3ccFfhT6U6_qu_TJ2mfTz-DZEf5wp5yeO0uHWc7zuP4RjhiFiiPO3AN_2799-PcJm348HIp7cP_LNfOM-rZAzrawuFhZBXWfnvKCf8dHnbp3QkvjP3DW7z9_gIcGY_7Nc7gdwfK3359sf7ViW_ehXtik1tkTbESGV8EazgQUgbL8FfWj385PW026sef1vYO79Z081w_GZSNnlb7rC9v5mwymysV3hdHuTebqzRqv7ROTk5rjS0F8zdGfjSqJ25keDQkWDoEYV709kkNog__TkkH3trcpthXl-7ST79Xr30OmZrM-dJDCDuQmM9_gRR54_Jg6YVByZ8HzfA_P3Pz0AXhnejv-noLfPjyaVQdNGqNo8Pa6WGjZdVbZ81T-N-_arWzWg3u4kErrsZbxkCOAq2jz29R4CiuwBuGUo7845PT18sHBQK4fzKJRqdJELp9Jw-JPt3p5tbxTFPQMhabbt4ebs7ySMkYChj4nq1gkdXldxBmxGLmN4qJWh-mxGQ1QSwsJqroS4nJrO0rLCeqk0vJwRVz-TclAUszZWy5b0pIFgHepiqKCItOxMoYavGzsfJt9ho5EbVKyUnxqydBrbSgTYI7-5dsmEr6lyC6UvDiUUlU6uKJ4qj8W__s9SP8krp-CsQUlBC1i0s_IcnGcQUlRCQ-",
                "ER|v1|317537|6/6|JSGHyReUE7UvTA-lZCPDghIGub4xfuJR_qP37OX1SZ6z0hcLmPjAF4riV49welr5DLBeUMj15Gk1kRJyDetRNlnIYP4TP2HTCTL9ExI9IQsKMMIbfJo9hGCq0PgCTxNvvXqEwtNTaxKKF5QQHViWkpA8uqyggOgAq5SA9FFWBUVsNsek3TXaJlPw2lEZcoazwwXJBS8fFYRneOtUaXhBGVFtcDoISFYJF5QQsfWUhDhlL2okCy5fyzGS58gfglngrl-Ix54VMboEEZmOdCRnELWzS2nHbsJbBfTCBsyZk2YPloY9bqug-MWjszYzHHXq1M2iMjbnU6ZlxE6qLHj56DTH9CCNn-tY8PrReXUZMyU-ua6odTb9tTOsk-y0XVBE1JM6e8EQdacuaqBNt920gVDf3YIXjxrnptdu8Ra6RUfPpgduhotD3XALXn3b9SFjaZPs_1BUxqYpQYaIZHuCgiKi_gLZK4Oo00DBy0dl-emgPVmgX1BCVMqekpAoai94_WhPa0YsF9vdWvD60SbQ1PUT20GLeojNDsW0h0jsVSwqYLNfLUNAeudaUUc0ylsyJfc0PQk4SguI7avJ9hjjvCxCssY9_2e8LCXq6JsOK2K9fV_IvLwsKKpNTw_drCr1n5YXnRuZ59C3J0i-kO15WVa0myt_GRrt6_ppYWbuHctqN5yfFUjtBMr2CJ282xY_ZjL_fr1OTlTpnpE73da8_7SU3GRzas_Wz4rqaFZemqsjwj0XimkwCi3lPe-vXyfNvM53FrHuBj8rKNqVl44CYvvzflZOtD8sfadiO8Xyx3fmFq3sx_c8PwVmr2u1Q7D17Jh4vbCof3TeWnfTSTrfgq8XFm0ES4_B-JawfDNmbCvKTgTlUolYo4B8E75WkJ471B_XSTBjveRpXy3KzJ_oE3uZNhQyLSyHiGfncnq5aCe5_WhHAs08YyZ3DO1GnpH7WGds8tmRyEmeH07vy9mNxGgDTcYyJrWVZjcit1te0tmb1OaX3YiMdqlkzKXx_Sq7kffYF8syL37Pk_vYICvw5Pe3uE9UCpEtF51XnxYZnW2_I2noOJaMjPqM7_SXpTqN5Zh026Fsh5JT9TOZwmOVNDuTv-2Wn57y433zX1iHvHYmifYVZ2Tf0A7jXUnLBdCx5jMvLEWypH35dLC8Cy_48saP_wc"
            ],
            "qr": [
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"512\" height=\"512\" viewBox=\"0 0 512 512\"><rect x=\"0\" y=\"0\" width=\"512\" height=\"512\" fill=\"#fefefe\"/><g transform=\"scale(3.436)\"><g transform=\"translate(16.000,16.000)\"><path fill-rule=\"evenodd\" d=\"M8 0L8 4L10 4L10 5L8 5L8 7L9 7L9 11L8 11L8 9L7 9L7 8L4 8L4 9L3 9L3 10L4 10L4 11L5 11L5 12L4 12L4 13L3 13L3 12L2 12L2 11L1 11L1 10L0 10L0 12L1 12L1 13L0 13L0 18L1 18L1 20L0 20L0 24L1 24L1 20L2 20L2 18L1 18L1 17L2 17L2 16L1 16L1 14L4 14L4 15L3 15L3 17L4 17L4 18L5 18L5 22L3 22L3 23L2 23L2 24L3 24L3 26L2 26L2 25L0 25L0 26L1 26L1 27L0 27L0 28L1 28L1 29L2 29L2 28L1 28L1 27L3 27L3 28L4 28L4 29L3 29L3 30L2 30L2 31L4 31L4 32L2 32L2 33L0 33L0 37L1 37L1 40L0 40L0 42L2 42L2 41L3 41L3 40L4 40L4 38L2 38L2 37L3 37L3 36L2 36L2 35L7 35L7 36L4 36L4 37L5 37L5 38L6 38L6 39L7 39L7 38L8 38L8 39L10 39L10 38L8 38L8 37L11 37L11 39L12 39L12 41L11 41L11 40L9 40L9 41L10 41L10 42L9 42L9 44L8 44L8 43L7 43L7 42L8 42L8 40L5 40L5 41L7 41L7 42L5 42L5 43L7 43L7 44L6 44L6 45L8 45L8 46L6 46L6 47L3 47L3 44L4 44L4 46L5 46L5 44L4 44L4 42L3 42L3 43L2 43L2 44L1 44L1 43L0 43L0 44L1 44L1 45L0 45L0 46L1 46L1 45L2 45L2 48L3 48L3 49L4 49L4 52L3 52L3 50L1 50L1 49L0 49L0 52L1 52L1 53L0 53L0 58L1 58L1 59L0 59L0 60L1 60L1 59L3 59L3 57L4 57L4 60L2 60L2 61L0 61L0 64L1 64L1 63L2 63L2 64L5 64L5 65L7 65L7 66L5 66L5 67L4 67L4 69L0 69L0 72L1 72L1 73L0 73L0 74L1 74L1 75L0 75L0 77L2 77L2 74L1 74L1 73L3 73L3 76L4 76L4 77L5 77L5 79L3 79L3 78L0 78L0 80L5 80L5 79L8 79L8 78L6 78L6 77L7 77L7 76L8 76L8 77L9 77L9 78L10 78L10 76L8 76L8 75L7 75L7 74L6 74L6 73L10 73L10 75L11 75L11 76L12 76L12 77L14 77L14 80L15 80L15 79L16 79L16 80L17 80L17 82L16 82L16 81L13 81L13 82L12 82L12 80L11 80L11 79L13 79L13 78L11 78L11 79L10 79L10 81L8 81L8 80L6 80L6 81L5 81L5 82L4 82L4 81L2 81L2 82L1 82L1 83L2 83L2 82L3 82L3 83L4 83L4 87L3 87L3 86L2 86L2 84L1 84L1 85L0 85L0 88L1 88L1 87L2 87L2 89L1 89L1 90L2 90L2 91L0 91L0 93L2 93L2 95L3 95L3 96L2 96L2 100L3 100L3 98L5 98L5 97L8 97L8 98L6 98L6 99L8 99L8 98L10 98L10 97L11 97L11 96L12 96L12 97L13 97L13 98L11 98L11 100L10 100L10 101L9 101L9 100L6 100L6 101L5 101L5 99L4 99L4 101L0 101L0 102L3 102L3 103L4 103L4 105L2 105L2 103L0 103L0 107L1 107L1 106L2 106L2 108L1 108L1 109L3 109L3 106L4 106L4 107L5 107L5 108L6 108L6 109L7 109L7 108L8 108L8 110L9 110L9 111L8 111L8 113L9 113L9 115L8 115L8 117L9 117L9 116L10 116L10 113L9 113L9 112L10 112L10 110L9 110L9 109L10 109L10 108L11 108L11 107L10 107L10 106L11 106L11 105L12 105L12 104L13 104L13 107L12 107L12 110L11 110L11 113L12 113L12 112L13 112L13 114L15 114L15 115L14 115L14 116L13 116L13 117L14 117L14 116L15 116L15 117L16 117L16 116L17 116L17 117L18 117L18 116L17 116L17 115L18 115L18 114L17 114L17 112L16 112L16 114L15 114L15 112L13 112L13 109L14 109L14 110L15 110L15 111L17 111L17 110L19 110L19 109L20 109L20 110L21 110L21 111L22 111L22 108L21 108L21 107L23 107L23 111L24 111L24 110L25 110L25 111L26 111L26 110L29 110L29 111L28 111L28 112L26 112L26 113L25 113L25 112L23 112L23 113L22 113L22 112L21 112L21 113L20 113L20 114L21 114L21 115L22 115L22 114L23 114L23 113L24 113L24 115L23 115L23 116L22 116L22 117L24 117L24 115L26 115L26 114L33 114L33 113L34 113L34 115L37 115L37 116L35 116L35 117L39 117L39 115L38 115L38 113L39 113L39 114L40 114L40 113L41 113L41 114L42 114L42 113L41 113L41 112L43 112L43 114L44 114L44 113L45 113L45 114L46 114L46 115L45 115L45 116L47 116L47 113L46 113L46 112L44 112L44 111L47 111L47 110L48 110L48 109L47 109L47 108L46 108L46 107L48 107L48 108L50 108L50 106L51 106L51 108L53 108L53 110L52 110L52 109L50 109L50 110L52 110L52 112L51 112L51 111L49 111L49 112L50 112L50 113L53 113L53 114L52 114L52 115L51 115L51 117L52 117L52 116L54 116L54 117L55 117L55 116L56 116L56 117L57 117L57 116L56 116L56 115L57 115L57 114L58 114L58 116L59 116L59 117L60 117L60 116L59 116L59 113L61 113L61 111L62 111L62 110L63 110L63 109L62 109L62 108L61 108L61 107L62 107L62 105L61 105L61 107L60 107L60 105L59 105L59 106L58 106L58 105L57 105L57 107L58 107L58 108L56 108L56 113L53 113L53 112L54 112L54 111L53 111L53 110L55 110L55 108L54 108L54 107L52 107L52 106L55 106L55 107L56 107L56 104L57 104L57 103L58 103L58 104L61 104L61 103L62 103L62 102L63 102L63 103L65 103L65 102L64 102L64 101L65 101L65 100L66 100L66 99L65 99L65 98L68 98L68 94L69 94L69 92L68 92L68 91L66 91L66 90L67 90L67 89L63 89L63 90L62 90L62 89L61 89L61 88L60 88L60 89L59 89L59 90L58 90L58 88L59 88L59 87L62 87L62 88L63 88L63 87L62 87L62 86L61 86L61 85L63 85L63 83L64 83L64 86L65 86L65 87L64 87L64 88L65 88L65 87L66 87L66 88L68 88L68 87L73 87L73 88L71 88L71 90L70 90L70 88L69 88L69 89L68 89L68 90L70 90L70 92L72 92L72 91L74 91L74 90L75 90L75 91L76 91L76 92L75 92L75 93L74 93L74 92L73 92L73 93L74 93L74 94L72 94L72 93L71 93L71 94L70 94L70 95L71 95L71 96L72 96L72 97L71 97L71 98L70 98L70 97L69 97L69 98L70 98L70 99L69 99L69 101L66 101L66 102L68 102L68 103L66 103L66 105L65 105L65 104L64 104L64 105L63 105L63 107L64 107L64 108L66 108L66 109L67 109L67 111L66 111L66 110L65 110L65 111L63 111L63 112L62 112L62 113L63 113L63 112L64 112L64 114L65 114L65 115L62 115L62 114L61 114L61 115L62 115L62 116L61 116L61 117L63 117L63 116L65 116L65 117L67 117L67 116L68 116L68 117L74 117L74 116L75 116L75 117L76 117L76 116L77 116L77 117L79 117L79 116L80 116L80 117L81 117L81 116L82 116L82 115L84 115L84 114L85 114L85 115L86 115L86 114L85 114L85 113L89 113L89 112L90 112L90 113L91 113L91 112L92 112L92 110L94 110L94 111L93 111L93 112L94 112L94 113L93 113L93 114L92 114L92 115L90 115L90 114L89 114L89 115L88 115L88 114L87 114L87 115L88 115L88 116L85 116L85 117L89 117L89 115L90 115L90 117L91 117L91 116L92 116L92 115L93 115L93 116L94 116L94 115L95 115L95 114L96 114L96 115L98 115L98 116L96 116L96 117L98 117L98 116L99 116L99 115L100 115L100 117L103 117L103 116L104 116L104 117L105 117L105 116L107 116L107 114L109 114L109 113L111 113L111 114L112 114L112 116L110 116L110 115L108 115L108 117L109 117L109 116L110 116L110 117L112 117L112 116L113 116L113 114L114 114L114 113L113 113L113 111L116 111L116 110L117 110L117 108L116 108L116 106L115 106L115 108L111 108L111 107L108 107L108 106L112 106L112 107L114 107L114 106L112 106L112 105L114 105L114 103L115 103L115 104L116 104L116 105L117 105L117 104L116 104L116 103L115 103L115 101L114 101L114 102L112 102L112 101L108 101L108 102L107 102L107 99L108 99L108 100L111 100L111 99L110 99L110 98L109 98L109 99L108 99L108 97L107 97L107 96L110 96L110 94L111 94L111 98L112 98L112 97L114 97L114 96L113 96L113 95L116 95L116 94L117 94L117 91L116 91L116 90L117 90L117 87L116 87L116 81L117 81L117 80L116 80L116 81L115 81L115 80L114 80L114 82L111 82L111 81L112 81L112 80L111 80L111 79L109 79L109 77L108 77L108 76L104 76L104 75L105 75L105 74L106 74L106 75L109 75L109 73L110 73L110 75L111 75L111 76L110 76L110 78L112 78L112 75L113 75L113 76L114 76L114 75L115 75L115 73L116 73L116 72L117 72L117 71L116 71L116 70L115 70L115 69L114 69L114 70L113 70L113 71L116 71L116 72L114 72L114 74L113 74L113 73L112 73L112 70L111 70L111 69L112 69L112 67L113 67L113 66L114 66L114 65L115 65L115 67L117 67L117 63L116 63L116 62L117 62L117 60L116 60L116 62L115 62L115 64L113 64L113 66L112 66L112 67L109 67L109 65L110 65L110 66L111 66L111 65L112 65L112 64L111 64L111 61L115 61L115 60L113 60L113 59L116 59L116 58L117 58L117 55L116 55L116 54L117 54L117 53L116 53L116 54L115 54L115 55L116 55L116 58L115 58L115 57L113 57L113 55L114 55L114 54L110 54L110 53L112 53L112 52L113 52L113 53L115 53L115 52L117 52L117 51L116 51L116 50L117 50L117 48L116 48L116 49L115 49L115 48L113 48L113 46L115 46L115 47L116 47L116 46L117 46L117 43L116 43L116 42L117 42L117 41L115 41L115 42L113 42L113 43L112 43L112 44L111 44L111 43L109 43L109 44L110 44L110 45L109 45L109 46L107 46L107 45L108 45L108 43L107 43L107 41L106 41L106 39L107 39L107 40L108 40L108 41L109 41L109 38L110 38L110 42L111 42L111 41L114 41L114 38L115 38L115 39L116 39L116 37L117 37L117 36L116 36L116 37L114 37L114 36L113 36L113 34L114 34L114 35L116 35L116 33L115 33L115 34L114 34L114 31L116 31L116 32L117 32L117 31L116 31L116 30L117 30L117 29L116 29L116 25L117 25L117 23L116 23L116 22L117 22L117 20L116 20L116 18L117 18L117 15L116 15L116 13L117 13L117 12L115 12L115 14L113 14L113 10L114 10L114 11L116 11L116 10L117 10L117 8L113 8L113 9L112 9L112 8L111 8L111 10L110 10L110 9L109 9L109 6L108 6L108 5L107 5L107 4L106 4L106 3L109 3L109 1L108 1L108 2L106 2L106 1L107 1L107 0L106 0L106 1L103 1L103 0L102 0L102 3L101 3L101 0L100 0L100 1L98 1L98 0L97 0L97 1L98 1L98 2L99 2L99 3L98 3L98 4L97 4L97 3L95 3L95 4L96 4L96 5L98 5L98 7L97 7L97 6L96 6L96 7L97 7L97 8L96 8L96 9L95 9L95 10L94 10L94 7L95 7L95 6L94 6L94 5L93 5L93 6L92 6L92 7L93 7L93 9L91 9L91 5L92 5L92 4L93 4L93 3L92 3L92 1L93 1L93 0L92 0L92 1L91 1L91 0L90 0L90 1L86 1L86 0L83 0L83 2L82 2L82 0L80 0L80 1L81 1L81 2L82 2L82 3L81 3L81 4L80 4L80 7L79 7L79 3L80 3L80 2L79 2L79 1L77 1L77 0L74 0L74 2L73 2L73 0L72 0L72 2L73 2L73 3L72 3L72 4L73 4L73 6L72 6L72 7L73 7L73 9L72 9L72 10L69 10L69 9L70 9L70 8L71 8L71 6L70 6L70 4L71 4L71 2L70 2L70 0L69 0L69 2L70 2L70 4L68 4L68 5L67 5L67 3L68 3L68 0L66 0L66 1L65 1L65 2L64 2L64 0L60 0L60 1L59 1L59 2L58 2L58 1L57 1L57 0L56 0L56 1L57 1L57 4L56 4L56 5L53 5L53 4L54 4L54 3L53 3L53 2L55 2L55 1L54 1L54 0L53 0L53 1L52 1L52 3L49 3L49 4L50 4L50 7L49 7L49 6L48 6L48 4L47 4L47 3L45 3L45 4L44 4L44 3L43 3L43 1L44 1L44 2L45 2L45 1L46 1L46 0L45 0L45 1L44 1L44 0L41 0L41 2L40 2L40 0L39 0L39 1L37 1L37 4L36 4L36 5L35 5L35 4L33 4L33 3L34 3L34 1L35 1L35 2L36 2L36 0L34 0L34 1L32 1L32 0L30 0L30 1L29 1L29 0L28 0L28 1L29 1L29 2L28 2L28 4L30 4L30 7L29 7L29 6L28 6L28 5L27 5L27 4L26 4L26 3L27 3L27 0L26 0L26 1L24 1L24 0L23 0L23 1L22 1L22 0L17 0L17 2L18 2L18 1L19 1L19 3L20 3L20 1L22 1L22 2L21 2L21 4L23 4L23 3L22 3L22 2L25 2L25 4L24 4L24 5L25 5L25 4L26 4L26 7L25 7L25 6L24 6L24 7L23 7L23 6L22 6L22 7L23 7L23 8L26 8L26 9L28 9L28 8L29 8L29 9L31 9L31 10L30 10L30 12L29 12L29 10L27 10L27 11L28 11L28 12L29 12L29 15L28 15L28 14L27 14L27 15L26 15L26 13L27 13L27 12L26 12L26 10L25 10L25 11L24 11L24 9L22 9L22 8L21 8L21 5L20 5L20 4L17 4L17 3L16 3L16 2L15 2L15 3L14 3L14 2L13 2L13 0L11 0L11 1L10 1L10 0ZM14 0L14 1L15 1L15 0ZM49 0L49 1L50 1L50 2L51 2L51 1L50 1L50 0ZM95 0L95 1L96 1L96 0ZM31 1L31 2L29 2L29 3L31 3L31 4L32 4L32 3L33 3L33 2L32 2L32 1ZM61 1L61 2L59 2L59 4L63 4L63 8L62 8L62 12L63 12L63 14L62 14L62 13L61 13L61 11L60 11L60 10L61 10L61 9L57 9L57 10L56 10L56 6L55 6L55 9L54 9L54 10L49 10L49 7L48 7L48 6L47 6L47 4L45 4L45 5L43 5L43 8L42 8L42 6L41 6L41 5L42 5L42 3L41 3L41 5L40 5L40 2L39 2L39 4L38 4L38 5L37 5L37 7L36 7L36 6L35 6L35 8L40 8L40 10L39 10L39 9L37 9L37 10L35 10L35 11L31 11L31 12L32 12L32 13L31 13L31 15L29 15L29 16L28 16L28 15L27 15L27 17L26 17L26 15L25 15L25 14L24 14L24 13L22 13L22 14L21 14L21 12L22 12L22 11L23 11L23 12L24 12L24 11L23 11L23 10L22 10L22 11L21 11L21 8L20 8L20 5L19 5L19 8L18 8L18 5L15 5L15 4L16 4L16 3L15 3L15 4L14 4L14 5L13 5L13 4L12 4L12 3L13 3L13 2L12 2L12 3L11 3L11 2L9 2L9 3L10 3L10 4L12 4L12 5L13 5L13 8L12 8L12 9L11 9L11 8L10 8L10 9L11 9L11 11L9 11L9 12L11 12L11 13L10 13L10 16L11 16L11 18L10 18L10 17L9 17L9 19L11 19L11 21L10 21L10 20L8 20L8 19L6 19L6 20L8 20L8 22L9 22L9 23L8 23L8 25L10 25L10 24L13 24L13 22L14 22L14 23L15 23L15 22L14 22L14 21L12 21L12 19L14 19L14 20L15 20L15 18L16 18L16 19L17 19L17 20L18 20L18 19L19 19L19 22L18 22L18 21L17 21L17 22L18 22L18 23L16 23L16 25L15 25L15 24L14 24L14 25L15 25L15 26L13 26L13 27L12 27L12 28L11 28L11 27L10 27L10 26L7 26L7 25L6 25L6 24L7 24L7 23L6 23L6 24L5 24L5 23L4 23L4 25L6 25L6 26L3 26L3 27L6 27L6 28L7 28L7 29L5 29L5 30L7 30L7 29L10 29L10 30L11 30L11 32L10 32L10 33L9 33L9 35L8 35L8 36L7 36L7 37L6 37L6 38L7 38L7 37L8 37L8 36L10 36L10 34L11 34L11 35L12 35L12 39L13 39L13 40L14 40L14 39L15 39L15 40L16 40L16 41L12 41L12 42L10 42L10 43L12 43L12 44L11 44L11 45L12 45L12 44L14 44L14 45L15 45L15 48L16 48L16 47L17 47L17 48L18 48L18 47L17 47L17 44L18 44L18 46L19 46L19 45L20 45L20 43L23 43L23 45L22 45L22 44L21 44L21 45L22 45L22 47L20 47L20 48L19 48L19 51L18 51L18 50L16 50L16 49L15 49L15 51L14 51L14 49L13 49L13 50L12 50L12 51L11 51L11 49L12 49L12 48L14 48L14 46L12 46L12 48L11 48L11 49L9 49L9 48L10 48L10 47L8 47L8 48L7 48L7 47L6 47L6 48L4 48L4 49L6 49L6 50L8 50L8 49L9 49L9 50L10 50L10 53L9 53L9 54L10 54L10 53L11 53L11 54L13 54L13 56L12 56L12 55L11 55L11 56L10 56L10 57L9 57L9 58L10 58L10 59L9 59L9 61L10 61L10 62L9 62L9 63L6 63L6 64L7 64L7 65L8 65L8 66L7 66L7 67L5 67L5 69L4 69L4 71L5 71L5 69L6 69L6 70L9 70L9 71L6 71L6 72L9 72L9 71L10 71L10 69L11 69L11 70L12 70L12 72L14 72L14 74L15 74L15 75L17 75L17 76L18 76L18 77L19 77L19 78L20 78L20 79L18 79L18 81L20 81L20 80L21 80L21 82L24 82L24 83L22 83L22 84L21 84L21 85L18 85L18 84L17 84L17 83L19 83L19 84L20 84L20 82L17 82L17 83L16 83L16 82L13 82L13 83L14 83L14 84L11 84L11 83L12 83L12 82L11 82L11 83L9 83L9 84L11 84L11 85L9 85L9 86L11 86L11 88L12 88L12 91L11 91L11 89L10 89L10 91L11 91L11 93L13 93L13 94L14 94L14 93L15 93L15 94L16 94L16 93L17 93L17 95L18 95L18 93L20 93L20 91L19 91L19 92L18 92L18 90L19 90L19 89L20 89L20 90L21 90L21 89L20 89L20 88L19 88L19 86L21 86L21 85L22 85L22 84L24 84L24 87L23 87L23 86L22 86L22 87L21 87L21 88L22 88L22 91L21 91L21 92L22 92L22 91L23 91L23 93L21 93L21 94L20 94L20 95L19 95L19 96L16 96L16 95L15 95L15 96L14 96L14 100L16 100L16 102L17 102L17 103L16 103L16 104L14 104L14 106L15 106L15 107L13 107L13 108L15 108L15 109L16 109L16 108L18 108L18 109L19 109L19 108L18 108L18 107L19 107L19 105L21 105L21 104L22 104L22 103L24 103L24 104L23 104L23 107L24 107L24 109L25 109L25 110L26 110L26 109L25 109L25 108L26 108L26 107L27 107L27 108L28 108L28 109L29 109L29 110L30 110L30 109L29 109L29 108L28 108L28 106L29 106L29 104L28 104L28 105L27 105L27 104L26 104L26 105L27 105L27 106L26 106L26 107L24 107L24 104L25 104L25 103L24 103L24 102L22 102L22 101L24 101L24 100L26 100L26 99L27 99L27 102L26 102L26 101L25 101L25 102L26 102L26 103L28 103L28 101L29 101L29 103L30 103L30 105L31 105L31 107L32 107L32 108L33 108L33 103L35 103L35 104L36 104L36 106L37 106L37 107L35 107L35 108L37 108L37 111L35 111L35 114L36 114L36 113L38 113L38 112L37 112L37 111L39 111L39 112L40 112L40 111L39 111L39 110L40 110L40 109L42 109L42 110L41 110L41 111L44 111L44 109L42 109L42 107L43 107L43 106L44 106L44 105L45 105L45 104L46 104L46 106L45 106L45 107L44 107L44 108L45 108L45 110L46 110L46 108L45 108L45 107L46 107L46 106L48 106L48 107L49 107L49 106L50 106L50 105L49 105L49 106L48 106L48 104L49 104L49 103L48 103L48 102L47 102L47 104L46 104L46 102L45 102L45 103L44 103L44 105L43 105L43 106L38 106L38 105L39 105L39 104L40 104L40 101L41 101L41 103L43 103L43 102L42 102L42 101L41 101L41 100L42 100L42 99L43 99L43 101L44 101L44 100L45 100L45 101L48 101L48 100L50 100L50 99L51 99L51 101L52 101L52 102L50 102L50 101L49 101L49 102L50 102L50 103L51 103L51 106L52 106L52 105L55 105L55 104L56 104L56 102L53 102L53 100L54 100L54 99L55 99L55 100L56 100L56 101L57 101L57 100L62 100L62 101L63 101L63 100L64 100L64 99L63 99L63 98L65 98L65 96L64 96L64 95L67 95L67 94L68 94L68 92L67 92L67 93L66 93L66 91L65 91L65 90L63 90L63 91L61 91L61 90L60 90L60 91L58 91L58 90L57 90L57 88L56 88L56 86L54 86L54 83L55 83L55 85L56 85L56 81L57 81L57 80L58 80L58 82L59 82L59 81L61 81L61 82L62 82L62 83L61 83L61 84L62 84L62 83L63 83L63 82L65 82L65 81L66 81L66 79L67 79L67 81L68 81L68 82L66 82L66 83L65 83L65 84L66 84L66 87L67 87L67 86L69 86L69 85L72 85L72 84L73 84L73 86L74 86L74 88L73 88L73 90L74 90L74 88L76 88L76 90L77 90L77 91L79 91L79 92L78 92L78 93L77 93L77 92L76 92L76 93L77 93L77 95L78 95L78 96L76 96L76 95L75 95L75 94L74 94L74 95L72 95L72 96L73 96L73 98L74 98L74 99L72 99L72 98L71 98L71 99L72 99L72 101L70 101L70 102L69 102L69 103L68 103L68 104L67 104L67 106L64 106L64 107L66 107L66 108L67 108L67 109L68 109L68 111L67 111L67 112L66 112L66 113L65 113L65 114L66 114L66 115L65 115L65 116L66 116L66 115L68 115L68 116L69 116L69 115L70 115L70 114L71 114L71 116L73 116L73 114L74 114L74 115L75 115L75 116L76 116L76 115L75 115L75 113L76 113L76 114L78 114L78 115L77 115L77 116L78 116L78 115L80 115L80 116L81 116L81 115L82 115L82 114L84 114L84 113L81 113L81 115L80 115L80 112L81 112L81 111L82 111L82 109L81 109L81 107L82 107L82 108L83 108L83 106L82 106L82 105L80 105L80 104L81 104L81 103L80 103L80 104L78 104L78 103L79 103L79 102L77 102L77 101L81 101L81 102L82 102L82 101L83 101L83 103L82 103L82 104L83 104L83 103L85 103L85 104L86 104L86 105L84 105L84 107L87 107L87 108L88 108L88 107L89 107L89 108L90 108L90 109L92 109L92 108L96 108L96 109L94 109L94 110L97 110L97 109L98 109L98 112L97 112L97 111L94 111L94 112L95 112L95 113L94 113L94 114L93 114L93 115L94 115L94 114L95 114L95 113L97 113L97 114L98 114L98 115L99 115L99 114L98 114L98 113L99 113L99 112L100 112L100 111L102 111L102 112L101 112L101 113L100 113L100 114L101 114L101 113L102 113L102 112L103 112L103 113L104 113L104 112L105 112L105 111L106 111L106 112L107 112L107 111L108 111L108 110L107 110L107 109L108 109L108 108L107 108L107 107L106 107L106 106L105 106L105 104L107 104L107 106L108 106L108 105L112 105L112 103L111 103L111 102L108 102L108 103L111 103L111 104L107 104L107 103L105 103L105 102L103 102L103 101L105 101L105 99L106 99L106 98L107 98L107 97L105 97L105 95L106 95L106 96L107 96L107 95L106 95L106 92L107 92L107 93L108 93L108 94L110 94L110 93L108 93L108 89L110 89L110 91L109 91L109 92L111 92L111 93L112 93L112 94L114 94L114 93L113 93L113 92L115 92L115 91L114 91L114 89L115 89L115 90L116 90L116 87L115 87L115 83L114 83L114 85L113 85L113 87L111 87L111 88L108 88L108 83L107 83L107 85L106 85L106 81L107 81L107 79L105 79L105 77L100 77L100 78L101 78L101 80L102 80L102 81L103 81L103 82L101 82L101 81L100 81L100 79L99 79L99 77L97 77L97 75L99 75L99 76L100 76L100 75L101 75L101 76L103 76L103 75L104 75L104 74L105 74L105 73L109 73L109 72L110 72L110 71L108 71L108 69L111 69L111 68L108 68L108 66L107 66L107 65L108 65L108 64L106 64L106 65L105 65L105 63L106 63L106 62L108 62L108 63L109 63L109 64L110 64L110 65L111 65L111 64L110 64L110 63L109 63L109 62L110 62L110 61L109 61L109 62L108 62L108 61L107 61L107 60L108 60L108 59L107 59L107 60L105 60L105 59L106 59L106 57L108 57L108 56L107 56L107 55L108 55L108 54L107 54L107 53L110 53L110 51L111 51L111 52L112 52L112 51L113 51L113 52L115 52L115 49L114 49L114 51L113 51L113 48L112 48L112 47L111 47L111 46L112 46L112 45L113 45L113 44L115 44L115 45L116 45L116 43L113 43L113 44L112 44L112 45L110 45L110 46L109 46L109 47L110 47L110 48L109 48L109 49L107 49L107 47L106 47L106 45L105 45L105 44L107 44L107 43L104 43L104 46L105 46L105 47L104 47L104 48L103 48L103 50L102 50L102 51L103 51L103 50L104 50L104 49L105 49L105 51L104 51L104 52L102 52L102 53L103 53L103 54L101 54L101 55L102 55L102 56L103 56L103 54L104 54L104 52L105 52L105 51L106 51L106 52L107 52L107 53L106 53L106 54L105 54L105 55L104 55L104 61L106 61L106 62L105 62L105 63L104 63L104 62L102 62L102 61L103 61L103 60L102 60L102 59L103 59L103 57L102 57L102 58L99 58L99 56L100 56L100 54L99 54L99 53L100 53L100 52L99 52L99 53L98 53L98 52L97 52L97 50L95 50L95 51L92 51L92 54L93 54L93 55L91 55L91 54L89 54L89 53L88 53L88 55L86 55L86 54L87 54L87 53L86 53L86 54L82 54L82 52L83 52L83 50L84 50L84 49L83 49L83 48L84 48L84 47L86 47L86 48L85 48L85 49L86 49L86 52L88 52L88 51L90 51L90 52L91 52L91 49L90 49L90 50L89 50L89 49L87 49L87 47L89 47L89 48L90 48L90 47L91 47L91 46L92 46L92 44L93 44L93 47L92 47L92 49L93 49L93 50L94 50L94 49L96 49L96 48L97 48L97 49L98 49L98 51L101 51L101 49L102 49L102 47L103 47L103 46L102 46L102 45L103 45L103 42L101 42L101 43L102 43L102 45L100 45L100 42L98 42L98 39L103 39L103 41L105 41L105 39L106 39L106 38L108 38L108 37L107 37L107 35L108 35L108 36L109 36L109 37L110 37L110 36L111 36L111 40L112 40L112 37L113 37L113 36L112 36L112 35L108 35L108 33L106 33L106 32L105 32L105 31L106 31L106 30L105 30L105 29L107 29L107 30L109 30L109 29L107 29L107 27L104 27L104 26L105 26L105 25L107 25L107 26L108 26L108 28L109 28L109 27L110 27L110 29L111 29L111 28L113 28L113 31L114 31L114 30L116 30L116 29L114 29L114 28L115 28L115 27L112 27L112 25L113 25L113 26L115 26L115 25L116 25L116 23L112 23L112 25L111 25L111 19L112 19L112 18L113 18L113 22L115 22L115 19L114 19L114 17L116 17L116 15L113 15L113 16L114 16L114 17L112 17L112 18L111 18L111 19L110 19L110 18L108 18L108 20L109 20L109 22L108 22L108 23L109 23L109 24L110 24L110 25L109 25L109 26L108 26L108 25L107 25L107 23L106 23L106 24L105 24L105 22L102 22L102 21L103 21L103 20L102 20L102 21L99 21L99 22L98 22L98 23L102 23L102 24L100 24L100 26L99 26L99 25L98 25L98 26L97 26L97 23L96 23L96 21L94 21L94 20L93 20L93 19L94 19L94 18L95 18L95 17L94 17L94 18L93 18L93 16L95 16L95 15L92 15L92 14L89 14L89 12L88 12L88 11L87 11L87 10L86 10L86 9L85 9L85 10L84 10L84 11L83 11L83 12L82 12L82 11L81 11L81 9L80 9L80 13L78 13L78 12L79 12L79 10L76 10L76 6L77 6L77 8L78 8L78 9L79 9L79 8L78 8L78 6L77 6L77 5L78 5L78 2L77 2L77 1L75 1L75 4L74 4L74 3L73 3L73 4L74 4L74 5L75 5L75 8L74 8L74 9L73 9L73 10L74 10L74 12L73 12L73 11L68 11L68 9L67 9L67 8L65 8L65 7L66 7L66 6L65 6L65 5L66 5L66 4L65 4L65 5L64 5L64 2L63 2L63 3L62 3L62 1ZM84 1L84 3L83 3L83 4L84 4L84 3L86 3L86 4L87 4L87 5L88 5L88 4L89 4L89 5L91 5L91 4L92 4L92 3L91 3L91 2L90 2L90 3L88 3L88 2L86 2L86 1ZM31 2L31 3L32 3L32 2ZM76 2L76 3L77 3L77 2ZM104 2L104 4L105 4L105 5L104 5L104 6L103 6L103 7L102 7L102 6L101 6L101 7L102 7L102 8L101 8L101 9L103 9L103 10L100 10L100 8L99 8L99 9L98 9L98 8L97 8L97 10L98 10L98 12L97 12L97 11L92 11L92 10L89 10L89 11L92 11L92 13L93 13L93 14L95 14L95 13L96 13L96 14L97 14L97 13L98 13L98 12L100 12L100 11L101 11L101 12L102 12L102 13L100 13L100 14L98 14L98 15L101 15L101 16L103 16L103 18L102 18L102 17L101 17L101 18L100 18L100 16L99 16L99 18L98 18L98 19L103 19L103 18L104 18L104 21L106 21L106 22L107 22L107 21L106 21L106 20L105 20L105 19L106 19L106 18L107 18L107 17L108 17L108 15L107 15L107 16L106 16L106 14L108 14L108 13L107 13L107 11L110 11L110 13L109 13L109 15L110 15L110 16L109 16L109 17L111 17L111 11L112 11L112 10L111 10L111 11L110 11L110 10L108 10L108 9L107 9L107 8L108 8L108 6L107 6L107 8L106 8L106 9L107 9L107 11L105 11L105 10L104 10L104 9L103 9L103 7L104 7L104 8L105 8L105 7L106 7L106 6L105 6L105 5L106 5L106 4L105 4L105 3L106 3L106 2ZM87 3L87 4L88 4L88 3ZM99 3L99 4L100 4L100 5L101 5L101 4L100 4L100 3ZM102 3L102 4L103 4L103 3ZM51 4L51 5L52 5L52 4ZM76 4L76 5L77 5L77 4ZM10 5L10 6L9 6L9 7L10 7L10 6L11 6L11 7L12 7L12 6L11 6L11 5ZM14 5L14 8L13 8L13 9L14 9L14 11L13 11L13 10L12 10L12 11L11 11L11 12L12 12L12 11L13 11L13 12L14 12L14 11L15 11L15 12L17 12L17 13L16 13L16 14L17 14L17 16L16 16L16 17L18 17L18 18L17 18L17 19L18 19L18 18L19 18L19 19L20 19L20 18L21 18L21 16L22 16L22 15L23 15L23 17L22 17L22 18L23 18L23 19L21 19L21 20L20 20L20 21L21 21L21 20L22 20L22 21L23 21L23 23L22 23L22 24L21 24L21 23L20 23L20 22L19 22L19 24L17 24L17 26L15 26L15 27L14 27L14 28L15 28L15 29L16 29L16 30L13 30L13 28L12 28L12 31L13 31L13 33L14 33L14 31L15 31L15 32L16 32L16 33L15 33L15 34L12 34L12 32L11 32L11 34L12 34L12 35L13 35L13 38L15 38L15 37L16 37L16 36L17 36L17 37L18 37L18 36L20 36L20 38L21 38L21 40L23 40L23 42L24 42L24 43L27 43L27 42L28 42L28 41L27 41L27 42L26 42L26 41L25 41L25 39L26 39L26 37L28 37L28 38L27 38L27 39L28 39L28 40L29 40L29 43L28 43L28 44L27 44L27 46L29 46L29 47L26 47L26 44L24 44L24 45L25 45L25 47L24 47L24 46L23 46L23 48L21 48L21 49L23 49L23 48L24 48L24 49L25 49L25 51L26 51L26 52L24 52L24 50L22 50L22 51L21 51L21 52L20 52L20 51L19 51L19 52L20 52L20 53L19 53L19 54L20 54L20 53L21 53L21 54L22 54L22 53L21 53L21 52L23 52L23 53L24 53L24 54L23 54L23 55L22 55L22 56L20 56L20 58L19 58L19 61L18 61L18 60L17 60L17 61L16 61L16 62L17 62L17 61L18 61L18 62L19 62L19 61L20 61L20 64L22 64L22 65L21 65L21 66L22 66L22 67L20 67L20 66L19 66L19 65L16 65L16 66L14 66L14 64L15 64L15 63L13 63L13 64L12 64L12 65L13 65L13 66L14 66L14 68L15 68L15 69L14 69L14 70L13 70L13 71L14 71L14 72L15 72L15 70L16 70L16 73L17 73L17 74L18 74L18 73L17 73L17 72L18 72L18 70L19 70L19 71L20 71L20 69L21 69L21 73L20 73L20 72L19 72L19 75L20 75L20 74L21 74L21 73L22 73L22 72L24 72L24 73L23 73L23 76L22 76L22 75L21 75L21 76L22 76L22 77L20 77L20 76L19 76L19 77L20 77L20 78L21 78L21 79L22 79L22 78L23 78L23 77L28 77L28 78L27 78L27 79L25 79L25 80L24 80L24 79L23 79L23 81L24 81L24 82L25 82L25 84L26 84L26 85L25 85L25 86L28 86L28 88L27 88L27 87L25 87L25 88L24 88L24 89L23 89L23 90L25 90L25 89L26 89L26 90L27 90L27 89L28 89L28 88L30 88L30 87L31 87L31 88L32 88L32 89L33 89L33 90L34 90L34 88L35 88L35 85L36 85L36 87L38 87L38 88L36 88L36 90L35 90L35 91L34 91L34 92L31 92L31 91L32 91L32 90L31 90L31 91L30 91L30 89L29 89L29 90L28 90L28 92L26 92L26 91L25 91L25 92L24 92L24 93L26 93L26 94L25 94L25 95L24 95L24 94L23 94L23 95L24 95L24 96L25 96L25 95L26 95L26 97L25 97L25 98L24 98L24 97L21 97L21 96L22 96L22 95L20 95L20 96L19 96L19 98L20 98L20 99L26 99L26 98L28 98L28 97L27 97L27 95L26 95L26 94L28 94L28 96L29 96L29 99L28 99L28 100L31 100L31 101L32 101L32 102L31 102L31 105L32 105L32 102L34 102L34 101L37 101L37 102L36 102L36 103L39 103L39 102L38 102L38 101L37 101L37 100L38 100L38 98L39 98L39 99L40 99L40 100L41 100L41 98L43 98L43 99L44 99L44 98L45 98L45 99L47 99L47 97L48 97L48 99L50 99L50 98L49 98L49 97L52 97L52 96L53 96L53 98L51 98L51 99L52 99L52 100L53 100L53 99L54 99L54 98L56 98L56 99L60 99L60 96L61 96L61 99L62 99L62 98L63 98L63 97L64 97L64 96L63 96L63 95L64 95L64 93L65 93L65 91L64 91L64 93L63 93L63 92L60 92L60 93L62 93L62 94L61 94L61 95L59 95L59 92L58 92L58 93L57 93L57 95L54 95L54 96L53 96L53 95L52 95L52 93L53 93L53 94L56 94L56 93L55 93L55 91L54 91L54 92L53 92L53 89L55 89L55 90L56 90L56 92L57 92L57 90L56 90L56 89L55 89L55 88L54 88L54 87L53 87L53 85L52 85L52 84L53 84L53 79L54 79L54 80L55 80L55 79L56 79L56 78L58 78L58 80L60 80L60 79L62 79L62 80L61 80L61 81L65 81L65 79L62 79L62 77L58 77L58 76L59 76L59 75L60 75L60 76L61 76L61 75L62 75L62 76L63 76L63 78L65 78L65 77L64 77L64 76L65 76L65 74L64 74L64 72L65 72L65 71L63 71L63 72L61 72L61 73L60 73L60 71L58 71L58 68L60 68L60 67L61 67L61 69L59 69L59 70L61 70L61 71L62 71L62 70L63 70L63 67L64 67L64 68L66 68L66 69L65 69L65 70L66 70L66 69L67 69L67 70L68 70L68 67L69 67L69 66L70 66L70 69L69 69L69 72L68 72L68 71L66 71L66 73L68 73L68 74L67 74L67 76L66 76L66 77L67 77L67 79L69 79L69 80L68 80L68 81L69 81L69 82L68 82L68 83L69 83L69 82L70 82L70 83L73 83L73 84L74 84L74 85L76 85L76 86L75 86L75 87L76 87L76 88L78 88L78 89L79 89L79 88L80 88L80 86L81 86L81 89L80 89L80 90L79 90L79 91L81 91L81 93L80 93L80 92L79 92L79 93L78 93L78 94L79 94L79 96L78 96L78 97L76 97L76 96L75 96L75 97L74 97L74 98L75 98L75 99L76 99L76 100L80 100L80 99L76 99L76 98L81 98L81 101L82 101L82 98L81 98L81 97L83 97L83 98L85 98L85 96L86 96L86 95L87 95L87 93L88 93L88 92L89 92L89 95L88 95L88 101L87 101L87 100L86 100L86 99L84 99L84 100L83 100L83 101L84 101L84 100L85 100L85 101L86 101L86 103L88 103L88 104L87 104L87 105L86 105L86 106L87 106L87 107L88 107L88 106L89 106L89 104L92 104L92 106L91 106L91 105L90 105L90 106L91 106L91 108L92 108L92 106L93 106L93 107L98 107L98 109L99 109L99 107L98 107L98 106L99 106L99 105L100 105L100 104L101 104L101 103L100 103L100 102L102 102L102 101L103 101L103 100L104 100L104 98L105 98L105 97L103 97L103 99L102 99L102 96L103 96L103 95L105 95L105 94L103 94L103 93L105 93L105 92L104 92L104 91L107 91L107 90L106 90L106 87L107 87L107 86L106 86L106 87L105 87L105 84L104 84L104 83L105 83L105 82L104 82L104 83L103 83L103 84L101 84L101 83L100 83L100 81L98 81L98 82L97 82L97 81L96 81L96 83L95 83L95 82L94 82L94 80L95 80L95 79L98 79L98 78L96 78L96 75L97 75L97 73L98 73L98 74L99 74L99 73L100 73L100 72L101 72L101 74L102 74L102 73L103 73L103 74L104 74L104 71L105 71L105 70L106 70L106 72L108 72L108 71L107 71L107 70L106 70L106 69L108 69L108 68L106 68L106 67L107 67L107 66L106 66L106 67L105 67L105 68L104 68L104 66L105 66L105 65L104 65L104 63L101 63L101 62L100 62L100 61L101 61L101 59L97 59L97 58L98 58L98 56L99 56L99 55L97 55L97 54L98 54L98 53L97 53L97 52L94 52L94 53L95 53L95 54L94 54L94 55L93 55L93 56L92 56L92 57L91 57L91 60L90 60L90 59L88 59L88 58L89 58L89 57L90 57L90 56L89 56L89 55L88 55L88 56L86 56L86 55L84 55L84 56L82 56L82 54L81 54L81 53L80 53L80 54L79 54L79 55L81 55L81 56L82 56L82 57L80 57L80 56L78 56L78 57L79 57L79 58L80 58L80 59L79 59L79 60L78 60L78 61L80 61L80 62L81 62L81 63L82 63L82 64L80 64L80 63L78 63L78 62L77 62L77 61L76 61L76 62L77 62L77 63L76 63L76 64L75 64L75 63L74 63L74 61L75 61L75 60L74 60L74 61L71 61L71 58L73 58L73 59L74 59L74 58L73 58L73 57L71 57L71 58L70 58L70 60L69 60L69 62L70 62L70 63L67 63L67 64L66 64L66 63L62 63L62 62L61 62L61 59L62 59L62 58L63 58L63 59L64 59L64 61L65 61L65 62L66 62L66 61L67 61L67 62L68 62L68 58L67 58L67 59L66 59L66 58L65 58L65 59L64 59L64 58L63 58L63 57L64 57L64 56L65 56L65 54L67 54L67 56L66 56L66 57L68 57L68 54L67 54L67 52L68 52L68 51L65 51L65 50L64 50L64 49L65 49L65 46L67 46L67 47L66 47L66 49L67 49L67 50L69 50L69 51L74 51L74 50L75 50L75 51L76 51L76 52L77 52L77 53L76 53L76 54L75 54L75 55L73 55L73 56L74 56L74 57L76 57L76 56L77 56L77 55L76 55L76 54L77 54L77 53L79 53L79 52L80 52L80 51L82 51L82 50L81 50L81 49L80 49L80 47L82 47L82 46L83 46L83 47L84 47L84 45L86 45L86 43L88 43L88 44L89 44L89 46L91 46L91 44L92 44L92 43L91 43L91 44L90 44L90 43L88 43L88 41L89 41L89 39L87 39L87 38L90 38L90 37L91 37L91 36L92 36L92 35L90 35L90 37L89 37L89 34L90 34L90 33L89 33L89 32L90 32L90 31L88 31L88 30L89 30L89 27L87 27L87 28L86 28L86 29L84 29L84 27L86 27L86 26L88 26L88 25L87 25L87 24L85 24L85 25L84 25L84 24L83 24L83 23L82 23L82 24L83 24L83 25L81 25L81 26L79 26L79 27L82 27L82 28L81 28L81 29L80 29L80 28L79 28L79 29L78 29L78 27L77 27L77 25L79 25L79 24L80 24L80 23L81 23L81 22L84 22L84 23L86 23L86 22L84 22L84 21L85 21L85 20L84 20L84 21L81 21L81 20L83 20L83 19L84 19L84 18L85 18L85 19L86 19L86 21L87 21L87 23L88 23L88 24L89 24L89 25L90 25L90 26L91 26L91 28L92 28L92 26L91 26L91 24L92 24L92 23L91 23L91 24L90 24L90 23L89 23L89 22L90 22L90 19L88 19L88 20L87 20L87 19L86 19L86 18L88 18L88 17L89 17L89 18L90 18L90 15L88 15L88 13L87 13L87 12L86 12L86 10L85 10L85 12L84 12L84 13L83 13L83 14L84 14L84 15L86 15L86 14L87 14L87 15L88 15L88 17L86 17L86 16L84 16L84 17L82 17L82 12L81 12L81 13L80 13L80 14L81 14L81 16L80 16L80 15L78 15L78 14L77 14L77 15L75 15L75 16L76 16L76 17L74 17L74 14L75 14L75 12L76 12L76 13L77 13L77 12L78 12L78 11L75 11L75 12L74 12L74 13L72 13L72 12L71 12L71 13L72 13L72 14L70 14L70 12L68 12L68 11L67 11L67 12L66 12L66 11L64 11L64 12L66 12L66 13L64 13L64 16L65 16L65 17L64 17L64 18L63 18L63 19L62 19L62 17L63 17L63 15L62 15L62 14L60 14L60 11L59 11L59 10L58 10L58 11L56 11L56 12L55 12L55 11L54 11L54 12L55 12L55 13L54 13L54 14L55 14L55 15L54 15L54 16L56 16L56 19L57 19L57 18L58 18L58 20L57 20L57 21L55 21L55 20L54 20L54 21L53 21L53 23L52 23L52 22L51 22L51 23L52 23L52 25L53 25L53 26L55 26L55 24L54 24L54 25L53 25L53 23L54 23L54 22L56 22L56 23L62 23L62 24L63 24L63 23L64 23L64 24L65 24L65 23L66 23L66 24L67 24L67 25L66 25L66 26L67 26L67 27L64 27L64 28L63 28L63 27L62 27L62 26L64 26L64 25L61 25L61 27L60 27L60 26L59 26L59 24L58 24L58 27L57 27L57 24L56 24L56 27L55 27L55 28L54 28L54 27L52 27L52 28L51 28L51 27L50 27L50 26L48 26L48 25L49 25L49 24L50 24L50 20L51 20L51 21L52 21L52 20L53 20L53 17L52 17L52 19L49 19L49 21L48 21L48 20L47 20L47 21L45 21L45 20L46 20L46 19L48 19L48 18L47 18L47 17L49 17L49 18L51 18L51 16L50 16L50 17L49 17L49 16L47 16L47 14L48 14L48 15L50 15L50 14L48 14L48 13L47 13L47 14L46 14L46 15L45 15L45 16L44 16L44 14L45 14L45 13L46 13L46 12L47 12L47 11L48 11L48 12L51 12L51 13L52 13L52 12L53 12L53 11L48 11L48 7L47 7L47 6L46 6L46 5L45 5L45 6L44 6L44 7L45 7L45 8L47 8L47 9L46 9L46 11L45 11L45 13L44 13L44 10L45 10L45 9L44 9L44 8L43 8L43 9L42 9L42 10L43 10L43 12L42 12L42 13L43 13L43 15L41 15L41 11L37 11L37 12L38 12L38 14L36 14L36 12L34 12L34 13L35 13L35 14L34 14L34 15L33 15L33 14L32 14L32 15L31 15L31 16L29 16L29 17L28 17L28 18L29 18L29 19L26 19L26 17L25 17L25 15L23 15L23 14L22 14L22 15L21 15L21 14L19 14L19 12L21 12L21 11L20 11L20 9L18 9L18 8L17 8L17 6L16 6L16 7L15 7L15 5ZM31 5L31 8L34 8L34 5ZM57 5L57 8L60 8L60 5ZM61 5L61 7L62 7L62 5ZM81 5L81 7L80 7L80 8L81 8L81 7L82 7L82 5ZM83 5L83 8L86 8L86 5ZM27 6L27 7L28 7L28 6ZM32 6L32 7L33 7L33 6ZM38 6L38 7L39 7L39 6ZM40 6L40 8L41 8L41 6ZM45 6L45 7L46 7L46 6ZM51 6L51 7L50 7L50 9L52 9L52 6ZM53 6L53 7L54 7L54 6ZM58 6L58 7L59 7L59 6ZM64 6L64 7L65 7L65 6ZM67 6L67 7L68 7L68 8L70 8L70 6L69 6L69 7L68 7L68 6ZM73 6L73 7L74 7L74 6ZM84 6L84 7L85 7L85 6ZM87 6L87 7L88 7L88 6ZM89 6L89 7L90 7L90 6ZM93 6L93 7L94 7L94 6ZM99 6L99 7L100 7L100 6ZM104 6L104 7L105 7L105 6ZM0 8L0 9L2 9L2 8ZM14 8L14 9L15 9L15 8ZM64 8L64 9L63 9L63 10L65 10L65 8ZM4 9L4 10L5 10L5 11L6 11L6 12L7 12L7 11L6 11L6 10L7 10L7 9ZM16 9L16 11L17 11L17 10L18 10L18 9ZM32 9L32 10L34 10L34 9ZM43 9L43 10L44 10L44 9ZM66 9L66 10L67 10L67 9ZM114 9L114 10L115 10L115 9ZM99 10L99 11L100 11L100 10ZM103 10L103 12L104 12L104 10ZM58 11L58 13L57 13L57 14L58 14L58 13L59 13L59 11ZM85 12L85 14L86 14L86 12ZM90 12L90 13L91 13L91 12ZM93 12L93 13L95 13L95 12ZM105 12L105 13L106 13L106 12ZM4 13L4 14L5 14L5 16L4 16L4 17L5 17L5 16L7 16L7 17L6 17L6 18L8 18L8 16L7 16L7 15L6 15L6 14L8 14L8 13ZM11 13L11 14L12 14L12 18L15 18L15 16L14 16L14 17L13 17L13 14L12 14L12 13ZM14 13L14 14L15 14L15 13ZM17 13L17 14L18 14L18 13ZM39 13L39 14L38 14L38 16L40 16L40 15L39 15L39 14L40 14L40 13ZM55 13L55 14L56 14L56 13ZM68 13L68 15L67 15L67 14L65 14L65 16L66 16L66 15L67 15L67 17L66 17L66 19L65 19L65 20L66 20L66 19L68 19L68 20L67 20L67 22L66 22L66 23L68 23L68 24L69 24L69 25L67 25L67 26L68 26L68 28L69 28L69 31L67 31L67 30L68 30L68 29L65 29L65 30L64 30L64 29L61 29L61 28L60 28L60 27L58 27L58 28L57 28L57 27L56 27L56 28L57 28L57 29L54 29L54 28L53 28L53 29L54 29L54 30L53 30L53 32L52 32L52 29L51 29L51 28L50 28L50 27L46 27L46 26L45 26L45 25L46 25L46 24L49 24L49 23L46 23L46 22L44 22L44 21L43 21L43 20L41 20L41 21L39 21L39 22L38 22L38 21L36 21L36 23L35 23L35 24L36 24L36 25L37 25L37 24L38 24L38 25L39 25L39 24L40 24L40 26L38 26L38 30L37 30L37 26L36 26L36 27L35 27L35 28L34 28L34 26L35 26L35 25L34 25L34 22L35 22L35 21L34 21L34 22L33 22L33 21L32 21L32 20L34 20L34 19L32 19L32 18L33 18L33 17L34 17L34 18L35 18L35 20L37 20L37 19L39 19L39 20L40 20L40 19L44 19L44 20L45 20L45 19L46 19L46 18L45 18L45 17L44 17L44 18L43 18L43 17L42 17L42 18L40 18L40 19L39 19L39 18L38 18L38 17L37 17L37 18L35 18L35 17L36 17L36 16L37 16L37 15L34 15L34 16L33 16L33 15L32 15L32 16L33 16L33 17L32 17L32 18L30 18L30 17L29 17L29 18L30 18L30 19L29 19L29 20L30 20L30 19L32 19L32 20L31 20L31 21L30 21L30 23L29 23L29 22L28 22L28 23L27 23L27 22L26 22L26 21L27 21L27 20L26 20L26 19L24 19L24 20L23 20L23 21L24 21L24 23L23 23L23 25L21 25L21 24L19 24L19 26L20 26L20 25L21 25L21 26L22 26L22 27L18 27L18 26L17 26L17 27L16 27L16 28L19 28L19 29L17 29L17 30L19 30L19 32L18 32L18 33L16 33L16 34L17 34L17 35L19 35L19 34L22 34L22 35L21 35L21 36L22 36L22 37L21 37L21 38L22 38L22 39L24 39L24 37L23 37L23 35L24 35L24 34L25 34L25 33L26 33L26 31L27 31L27 32L28 32L28 29L29 29L29 28L31 28L31 29L32 29L32 30L34 30L34 29L35 29L35 28L36 28L36 31L35 31L35 33L36 33L36 32L37 32L37 34L36 34L36 35L35 35L35 36L34 36L34 35L31 35L31 37L29 37L29 36L28 36L28 35L29 35L29 34L28 34L28 33L27 33L27 34L26 34L26 36L25 36L25 37L26 37L26 36L28 36L28 37L29 37L29 38L28 38L28 39L29 39L29 38L30 38L30 39L31 39L31 37L32 37L32 38L33 38L33 41L34 41L34 42L32 42L32 40L31 40L31 41L30 41L30 43L31 43L31 42L32 42L32 44L31 44L31 45L32 45L32 44L34 44L34 42L36 42L36 41L37 41L37 42L38 42L38 43L40 43L40 42L41 42L41 44L40 44L40 45L37 45L37 43L35 43L35 44L36 44L36 46L38 46L38 47L39 47L39 49L38 49L38 48L37 48L37 47L35 47L35 48L34 48L34 46L35 46L35 45L34 45L34 46L30 46L30 44L29 44L29 46L30 46L30 47L29 47L29 48L26 48L26 49L28 49L28 50L26 50L26 51L27 51L27 54L26 54L26 55L25 55L25 54L24 54L24 57L26 57L26 56L27 56L27 57L28 57L28 56L29 56L29 58L25 58L25 59L27 59L27 61L26 61L26 60L23 60L23 61L24 61L24 62L25 62L25 61L26 61L26 64L23 64L23 62L22 62L22 60L21 60L21 59L22 59L22 58L23 58L23 56L22 56L22 58L21 58L21 59L20 59L20 61L21 61L21 62L22 62L22 64L23 64L23 65L22 65L22 66L23 66L23 67L22 67L22 68L21 68L21 69L22 69L22 70L23 70L23 71L24 71L24 72L25 72L25 71L24 71L24 70L25 70L25 67L24 67L24 66L26 66L26 67L27 67L27 68L26 68L26 69L28 69L28 70L29 70L29 71L30 71L30 72L29 72L29 74L30 74L30 72L31 72L31 76L30 76L30 75L29 75L29 77L30 77L30 79L31 79L31 81L33 81L33 80L32 80L32 79L33 79L33 78L35 78L35 79L34 79L34 81L35 81L35 83L36 83L36 84L38 84L38 85L39 85L39 86L38 86L38 87L39 87L39 89L37 89L37 90L36 90L36 91L35 91L35 92L36 92L36 93L33 93L33 94L32 94L32 93L31 93L31 95L32 95L32 96L33 96L33 97L31 97L31 96L30 96L30 97L31 97L31 98L30 98L30 99L32 99L32 100L33 100L33 101L34 101L34 100L33 100L33 99L34 99L34 98L35 98L35 97L34 97L34 96L36 96L36 97L38 97L38 96L37 96L37 94L38 94L38 95L39 95L39 97L41 97L41 96L45 96L45 95L46 95L46 94L45 94L45 93L50 93L50 91L51 91L51 92L52 92L52 89L53 89L53 88L52 88L52 85L50 85L50 84L49 84L49 83L51 83L51 84L52 84L52 83L51 83L51 81L52 81L52 79L53 79L53 78L54 78L54 79L55 79L55 77L53 77L53 78L52 78L52 79L51 79L51 78L50 78L50 77L48 77L48 76L51 76L51 77L52 77L52 75L53 75L53 76L56 76L56 75L57 75L57 76L58 76L58 75L57 75L57 73L56 73L56 71L57 71L57 69L55 69L55 65L56 65L56 64L57 64L57 63L58 63L58 62L56 62L56 63L55 63L55 62L52 62L52 63L51 63L51 61L49 61L49 63L48 63L48 61L47 61L47 59L48 59L48 60L49 60L49 57L50 57L50 55L51 55L51 56L52 56L52 55L53 55L53 58L50 58L50 60L51 60L51 59L53 59L53 60L52 60L52 61L56 61L56 59L55 59L55 58L56 58L56 57L54 57L54 55L55 55L55 56L56 56L56 55L57 55L57 56L58 56L58 55L59 55L59 56L61 56L61 57L62 57L62 56L61 56L61 54L62 54L62 55L64 55L64 53L65 53L65 52L64 52L64 50L63 50L63 49L64 49L64 43L65 43L65 45L67 45L67 44L66 44L66 43L67 43L67 41L68 41L68 40L69 40L69 41L71 41L71 40L74 40L74 39L75 39L75 41L74 41L74 43L72 43L72 42L73 42L73 41L72 41L72 42L71 42L71 44L74 44L74 45L73 45L73 46L72 46L72 47L67 47L67 48L69 48L69 49L70 49L70 50L71 50L71 48L73 48L73 46L74 46L74 48L76 48L76 47L75 47L75 46L74 46L74 45L77 45L77 44L76 44L76 43L78 43L78 44L79 44L79 45L78 45L78 46L77 46L77 47L78 47L78 48L77 48L77 50L76 50L76 51L78 51L78 52L79 52L79 50L78 50L78 49L79 49L79 45L80 45L80 44L82 44L82 43L83 43L83 44L85 44L85 43L86 43L86 41L87 41L87 39L86 39L86 37L87 37L87 36L88 36L88 31L87 31L87 35L86 35L86 36L85 36L85 39L84 39L84 38L83 38L83 37L82 37L82 39L80 39L80 38L81 38L81 36L83 36L83 35L80 35L80 33L78 33L78 34L77 34L77 33L76 33L76 31L77 31L77 32L78 32L78 31L79 31L79 30L80 30L80 29L79 29L79 30L78 30L78 29L74 29L74 30L73 30L73 31L72 31L72 28L73 28L73 25L74 25L74 24L75 24L75 23L77 23L77 24L76 24L76 25L77 25L77 24L78 24L78 23L80 23L80 20L81 20L81 19L80 19L80 16L79 16L79 17L76 17L76 18L74 18L74 19L72 19L72 18L73 18L73 14L72 14L72 15L71 15L71 16L70 16L70 17L72 17L72 18L71 18L71 19L70 19L70 18L69 18L69 19L68 19L68 18L67 18L67 17L68 17L68 16L69 16L69 15L70 15L70 14L69 14L69 13ZM51 14L51 15L52 15L52 16L53 16L53 14ZM59 14L59 15L56 15L56 16L58 16L58 18L60 18L60 19L59 19L59 20L61 20L61 21L62 21L62 23L63 23L63 21L64 21L64 19L63 19L63 21L62 21L62 20L61 20L61 18L60 18L60 17L59 17L59 16L62 16L62 15L60 15L60 14ZM19 15L19 17L20 17L20 16L21 16L21 15ZM77 15L77 16L78 16L78 15ZM91 15L91 16L92 16L92 15ZM96 15L96 18L97 18L97 17L98 17L98 16L97 16L97 15ZM103 15L103 16L104 16L104 15ZM23 17L23 18L25 18L25 17ZM81 17L81 18L82 18L82 19L83 19L83 18L82 18L82 17ZM44 18L44 19L45 19L45 18ZM76 18L76 20L77 20L77 21L79 21L79 20L80 20L80 19L79 19L79 20L78 20L78 19L77 19L77 18ZM71 19L71 20L69 20L69 21L68 21L68 23L72 23L72 24L73 24L73 23L74 23L74 22L76 22L76 21L75 21L75 20L72 20L72 19ZM91 19L91 22L93 22L93 21L92 21L92 19ZM95 19L95 20L97 20L97 21L98 21L98 20L97 20L97 19ZM24 20L24 21L25 21L25 20ZM88 20L88 22L89 22L89 20ZM6 21L6 22L7 22L7 21ZM9 21L9 22L10 22L10 21ZM42 21L42 22L43 22L43 24L42 24L42 23L40 23L40 24L41 24L41 25L42 25L42 26L44 26L44 22L43 22L43 21ZM57 21L57 22L58 22L58 21ZM59 21L59 22L60 22L60 21ZM71 21L71 22L72 22L72 23L73 23L73 22L74 22L74 21ZM25 22L25 24L26 24L26 26L25 26L25 27L24 27L24 25L23 25L23 27L22 27L22 28L21 28L21 31L20 31L20 32L19 32L19 33L20 33L20 32L22 32L22 34L23 34L23 32L25 32L25 31L26 31L26 30L27 30L27 29L28 29L28 28L29 28L29 27L31 27L31 28L32 28L32 29L34 29L34 28L32 28L32 26L33 26L33 24L32 24L32 23L31 23L31 24L30 24L30 25L28 25L28 24L29 24L29 23L28 23L28 24L27 24L27 23L26 23L26 22ZM37 22L37 23L38 23L38 22ZM77 22L77 23L78 23L78 22ZM94 22L94 23L93 23L93 24L94 24L94 23L95 23L95 24L96 24L96 23L95 23L95 22ZM109 22L109 23L110 23L110 22ZM45 23L45 24L46 24L46 23ZM103 23L103 25L102 25L102 26L104 26L104 23ZM31 24L31 25L32 25L32 24ZM113 24L113 25L114 25L114 24ZM69 25L69 27L70 27L70 28L71 28L71 26L72 26L72 25L71 25L71 26L70 26L70 25ZM93 25L93 26L94 26L94 29L90 29L90 30L94 30L94 31L95 31L95 32L92 32L92 31L91 31L91 32L92 32L92 33L91 33L91 34L94 34L94 35L93 35L93 37L94 37L94 39L93 39L93 38L91 38L91 39L93 39L93 40L92 40L92 42L93 42L93 41L95 41L95 42L94 42L94 47L93 47L93 48L95 48L95 47L97 47L97 48L98 48L98 46L100 46L100 48L99 48L99 49L100 49L100 48L101 48L101 47L102 47L102 46L100 46L100 45L99 45L99 44L98 44L98 42L97 42L97 40L96 40L96 39L98 39L98 38L97 38L97 37L99 37L99 38L101 38L101 37L103 37L103 39L105 39L105 38L106 38L106 37L105 37L105 36L106 36L106 35L107 35L107 34L106 34L106 35L105 35L105 36L104 36L104 35L101 35L101 34L102 34L102 31L100 31L100 30L99 30L99 31L97 31L97 30L98 30L98 29L101 29L101 30L102 30L102 28L101 28L101 26L100 26L100 28L99 28L99 27L97 27L97 26L96 26L96 27L95 27L95 26L94 26L94 25ZM110 25L110 27L111 27L111 25ZM6 26L6 27L7 27L7 28L8 28L8 27L7 27L7 26ZM26 26L26 27L25 27L25 28L24 28L24 27L23 27L23 28L22 28L22 29L24 29L24 30L22 30L22 32L23 32L23 31L24 31L24 30L25 30L25 28L26 28L26 29L27 29L27 28L28 28L28 27L27 27L27 26ZM74 26L74 28L76 28L76 26ZM83 26L83 27L84 27L84 26ZM40 27L40 29L39 29L39 30L40 30L40 33L39 33L39 34L41 34L41 32L42 32L42 33L43 33L43 35L45 35L45 36L47 36L47 34L48 34L48 35L49 35L49 36L50 36L50 37L49 37L49 38L48 38L48 37L47 37L47 38L46 38L46 37L44 37L44 36L41 36L41 35L40 35L40 37L39 37L39 35L38 35L38 34L37 34L37 35L36 35L36 36L37 36L37 37L39 37L39 39L38 39L38 38L36 38L36 37L35 37L35 38L34 38L34 37L33 37L33 38L34 38L34 41L35 41L35 38L36 38L36 39L37 39L37 40L39 40L39 39L40 39L40 41L41 41L41 39L40 39L40 37L42 37L42 38L44 38L44 39L43 39L43 40L42 40L42 42L44 42L44 43L43 43L43 44L44 44L44 43L47 43L47 42L49 42L49 44L48 44L48 45L46 45L46 46L45 46L45 45L44 45L44 46L45 46L45 47L47 47L47 46L49 46L49 47L50 47L50 48L47 48L47 49L49 49L49 50L46 50L46 52L45 52L45 51L43 51L43 53L44 53L44 52L45 52L45 54L43 54L43 59L42 59L42 60L41 60L41 59L40 59L40 58L39 58L39 57L41 57L41 54L39 54L39 53L41 53L41 48L42 48L42 47L43 47L43 50L45 50L45 49L44 49L44 47L43 47L43 46L42 46L42 47L40 47L40 46L39 46L39 47L40 47L40 49L39 49L39 50L40 50L40 52L39 52L39 51L38 51L38 49L35 49L35 50L34 50L34 52L35 52L35 51L37 51L37 53L38 53L38 54L39 54L39 55L40 55L40 56L38 56L38 55L37 55L37 54L35 54L35 53L34 53L34 54L33 54L33 52L32 52L32 50L31 50L31 51L30 51L30 50L28 50L28 52L29 52L29 51L30 51L30 52L32 52L32 53L31 53L31 55L32 55L32 54L33 54L33 55L34 55L34 56L30 56L30 58L29 58L29 59L28 59L28 62L29 62L29 63L28 63L28 64L26 64L26 66L28 66L28 68L30 68L30 67L34 67L34 68L33 68L33 69L32 69L32 68L31 68L31 69L29 69L29 70L31 70L31 71L32 71L32 70L34 70L34 68L35 68L35 67L38 67L38 66L37 66L37 65L39 65L39 67L40 67L40 69L39 69L39 68L38 68L38 69L39 69L39 71L37 71L37 68L36 68L36 69L35 69L35 70L36 70L36 71L37 71L37 73L36 73L36 72L35 72L35 71L34 71L34 72L33 72L33 73L32 73L32 75L33 75L33 77L32 77L32 76L31 76L31 78L33 78L33 77L35 77L35 78L36 78L36 79L38 79L38 77L39 77L39 76L40 76L40 77L42 77L42 78L45 78L45 79L49 79L49 80L48 80L48 81L49 81L49 82L50 82L50 79L49 79L49 78L47 78L47 77L46 77L46 78L45 78L45 77L44 77L44 75L43 75L43 76L42 76L42 75L37 75L37 76L34 76L34 75L33 75L33 74L34 74L34 72L35 72L35 73L36 73L36 74L37 74L37 73L39 73L39 74L40 74L40 72L42 72L42 71L40 71L40 69L42 69L42 67L41 67L41 65L43 65L43 67L45 67L45 66L46 66L46 67L47 67L47 66L48 66L48 67L49 67L49 68L48 68L48 70L46 70L46 69L47 69L47 68L46 68L46 69L45 69L45 70L44 70L44 69L43 69L43 73L42 73L42 74L43 74L43 73L44 73L44 74L45 74L45 73L46 73L46 72L47 72L47 73L48 73L48 75L50 75L50 73L51 73L51 75L52 75L52 74L54 74L54 71L55 71L55 70L54 70L54 71L53 71L53 69L54 69L54 67L53 67L53 66L54 66L54 64L53 64L53 63L52 63L52 65L53 65L53 66L51 66L51 65L50 65L50 64L51 64L51 63L50 63L50 64L49 64L49 65L47 65L47 64L48 64L48 63L47 63L47 61L46 61L46 63L45 63L45 64L44 64L44 63L43 63L43 64L41 64L41 62L43 62L43 61L44 61L44 62L45 62L45 60L44 60L44 59L47 59L47 57L46 57L46 56L45 56L45 57L44 57L44 55L47 55L47 54L49 54L49 55L50 55L50 54L51 54L51 55L52 55L52 54L53 54L53 55L54 55L54 54L55 54L55 55L56 55L56 54L55 54L55 53L56 53L56 52L57 52L57 55L58 55L58 52L59 52L59 53L61 53L61 52L62 52L62 53L63 53L63 52L62 52L62 49L63 49L63 47L60 47L60 46L61 46L61 45L62 45L62 46L63 46L63 44L62 44L62 43L60 43L60 46L59 46L59 47L57 47L57 46L58 46L58 44L59 44L59 43L58 43L58 41L62 41L62 42L63 42L63 41L64 41L64 42L65 42L65 43L66 43L66 39L64 39L64 38L63 38L63 39L59 39L59 38L60 38L60 37L61 37L61 38L62 38L62 37L63 37L63 35L62 35L62 32L61 32L61 31L62 31L62 30L60 30L60 29L59 29L59 30L58 30L58 29L57 29L57 30L56 30L56 31L55 31L55 32L56 32L56 34L55 34L55 33L54 33L54 34L55 34L55 35L53 35L53 33L52 33L52 37L51 37L51 35L50 35L50 33L49 33L49 32L48 32L48 33L47 33L47 34L46 34L46 35L45 35L45 34L44 34L44 31L41 31L41 30L40 30L40 29L42 29L42 30L43 30L43 29L42 29L42 28L44 28L44 30L45 30L45 31L48 31L48 30L49 30L49 31L51 31L51 30L50 30L50 29L49 29L49 28L48 28L48 29L46 29L46 27L45 27L45 28L44 28L44 27ZM96 27L96 28L95 28L95 29L94 29L94 30L97 30L97 29L98 29L98 28L97 28L97 27ZM103 27L103 28L104 28L104 29L103 29L103 32L104 32L104 31L105 31L105 30L104 30L104 29L105 29L105 28L104 28L104 27ZM10 28L10 29L11 29L11 28ZM82 28L82 29L81 29L81 30L82 30L82 29L83 29L83 28ZM87 28L87 30L88 30L88 28ZM96 28L96 29L97 29L97 28ZM19 29L19 30L20 30L20 29ZM45 29L45 30L46 30L46 29ZM0 30L0 32L1 32L1 30ZM29 30L29 32L30 32L30 30ZM70 30L70 31L71 31L71 30ZM5 31L5 34L8 34L8 31ZM16 31L16 32L17 32L17 31ZM31 31L31 34L34 34L34 31ZM37 31L37 32L39 32L39 31ZM57 31L57 34L60 34L60 31ZM64 31L64 32L65 32L65 33L66 33L66 32L67 32L67 31ZM73 31L73 32L74 32L74 33L71 33L71 32L68 32L68 33L67 33L67 34L66 34L66 35L65 35L65 34L64 34L64 33L63 33L63 34L64 34L64 37L65 37L65 36L66 36L66 35L67 35L67 36L68 36L68 37L69 37L69 38L68 38L68 39L67 39L67 40L68 40L68 39L69 39L69 38L70 38L70 39L72 39L72 38L73 38L73 39L74 39L74 38L75 38L75 39L76 39L76 41L75 41L75 42L78 42L78 40L79 40L79 42L80 42L80 43L79 43L79 44L80 44L80 43L82 43L82 42L83 42L83 43L84 43L84 42L85 42L85 41L86 41L86 39L85 39L85 40L84 40L84 39L82 39L82 42L80 42L80 40L79 40L79 39L76 39L76 38L78 38L78 37L79 37L79 38L80 38L80 37L79 37L79 36L80 36L80 35L79 35L79 36L78 36L78 37L77 37L77 35L74 35L74 34L76 34L76 33L75 33L75 31ZM80 31L80 32L82 32L82 31ZM83 31L83 34L86 34L86 31ZM96 31L96 33L94 33L94 34L96 34L96 33L97 33L97 31ZM99 31L99 32L98 32L98 33L100 33L100 31ZM109 31L109 34L112 34L112 31ZM6 32L6 33L7 33L7 32ZM32 32L32 33L33 33L33 32ZM45 32L45 33L46 33L46 32ZM58 32L58 33L59 33L59 32ZM84 32L84 33L85 33L85 32ZM110 32L110 33L111 33L111 32ZM3 33L3 34L4 34L4 33ZM48 33L48 34L49 34L49 33ZM104 33L104 34L105 34L105 33ZM67 34L67 35L68 35L68 36L69 36L69 37L70 37L70 36L74 36L74 35L70 35L70 34L69 34L69 35L68 35L68 34ZM97 34L97 35L94 35L94 36L95 36L95 37L97 37L97 36L98 36L98 35L99 35L99 36L100 36L100 37L101 37L101 36L100 36L100 35L99 35L99 34ZM15 35L15 36L16 36L16 35ZM37 35L37 36L38 36L38 35ZM55 35L55 36L56 36L56 35ZM69 35L69 36L70 36L70 35ZM1 36L1 37L2 37L2 36ZM53 36L53 37L52 37L52 38L50 38L50 39L48 39L48 38L47 38L47 39L48 39L48 40L46 40L46 39L44 39L44 40L45 40L45 41L49 41L49 40L50 40L50 43L51 43L51 41L52 41L52 43L53 43L53 44L56 44L56 43L57 43L57 44L58 44L58 43L57 43L57 42L56 42L56 41L55 41L55 40L54 40L54 39L56 39L56 37L54 37L54 36ZM58 36L58 37L57 37L57 39L58 39L58 38L59 38L59 36ZM75 36L75 38L76 38L76 36ZM103 36L103 37L104 37L104 38L105 38L105 37L104 37L104 36ZM22 37L22 38L23 38L23 37ZM53 37L53 38L52 38L52 41L54 41L54 42L55 42L55 43L56 43L56 42L55 42L55 41L54 41L54 40L53 40L53 39L54 39L54 37ZM17 38L17 39L16 39L16 40L17 40L17 41L18 41L18 42L17 42L17 43L18 43L18 44L19 44L19 41L18 41L18 38ZM95 38L95 39L94 39L94 40L95 40L95 39L96 39L96 38ZM2 39L2 40L3 40L3 39ZM19 39L19 40L20 40L20 39ZM63 39L63 40L64 40L64 41L65 41L65 40L64 40L64 39ZM99 40L99 41L101 41L101 40ZM20 41L20 42L21 42L21 41ZM24 41L24 42L25 42L25 41ZM38 41L38 42L39 42L39 41ZM83 41L83 42L84 42L84 41ZM90 41L90 42L91 42L91 41ZM13 42L13 43L14 43L14 44L15 44L15 45L16 45L16 44L15 44L15 43L16 43L16 42ZM68 42L68 45L70 45L70 44L69 44L69 43L70 43L70 42ZM95 43L95 45L97 45L97 46L98 46L98 44L97 44L97 43ZM9 44L9 46L10 46L10 44ZM41 44L41 45L42 45L42 44ZM49 44L49 45L50 45L50 47L51 47L51 48L50 48L50 52L49 52L49 51L47 51L47 52L46 52L46 54L47 54L47 52L49 52L49 54L50 54L50 52L51 52L51 53L52 53L52 50L51 50L51 49L52 49L52 44L51 44L51 45L50 45L50 44ZM87 45L87 46L86 46L86 47L87 47L87 46L88 46L88 45ZM54 46L54 47L53 47L53 49L54 49L54 47L56 47L56 46ZM30 47L30 48L32 48L32 49L33 49L33 48L32 48L32 47ZM6 48L6 49L7 49L7 48ZM56 48L56 49L55 49L55 50L53 50L53 51L54 51L54 52L55 52L55 51L57 51L57 52L58 52L58 51L59 51L59 52L60 52L60 51L61 51L61 50L59 50L59 49L57 49L57 48ZM61 48L61 49L62 49L62 48ZM105 48L105 49L106 49L106 48ZM110 48L110 49L111 49L111 50L112 50L112 49L111 49L111 48ZM72 49L72 50L74 50L74 49ZM57 50L57 51L58 51L58 50ZM106 50L106 51L107 51L107 52L108 52L108 51L110 51L110 50ZM1 51L1 52L2 52L2 53L1 53L1 58L2 58L2 56L5 56L5 55L4 55L4 53L3 53L3 52L2 52L2 51ZM6 51L6 52L7 52L7 53L6 53L6 54L7 54L7 53L8 53L8 52L7 52L7 51ZM13 51L13 53L14 53L14 55L15 55L15 58L17 58L17 59L18 59L18 58L17 58L17 57L18 57L18 56L19 56L19 55L18 55L18 54L17 54L17 53L18 53L18 51L17 51L17 52L16 52L16 51L15 51L15 52L16 52L16 53L14 53L14 51ZM11 52L11 53L12 53L12 52ZM69 52L69 54L70 54L70 55L69 55L69 56L71 56L71 55L72 55L72 54L70 54L70 52ZM71 52L71 53L73 53L73 52ZM74 52L74 53L75 53L75 52ZM53 53L53 54L54 54L54 53ZM2 54L2 55L3 55L3 54ZM15 54L15 55L16 55L16 54ZM27 54L27 55L30 55L30 54ZM34 54L34 55L35 55L35 56L37 56L37 55L35 55L35 54ZM59 54L59 55L60 55L60 54ZM95 54L95 55L94 55L94 56L93 56L93 57L92 57L92 58L93 58L93 59L92 59L92 60L93 60L93 59L94 59L94 61L92 61L92 64L93 64L93 63L94 63L94 62L96 62L96 63L95 63L95 65L93 65L93 67L92 67L92 65L91 65L91 64L90 64L90 65L89 65L89 66L86 66L86 67L85 67L85 66L84 66L84 67L83 67L83 64L82 64L82 66L81 66L81 67L80 67L80 64L79 64L79 66L78 66L78 65L77 65L77 64L78 64L78 63L77 63L77 64L76 64L76 65L75 65L75 64L74 64L74 63L70 63L70 65L71 65L71 64L74 64L74 67L73 67L73 65L72 65L72 67L71 67L71 68L73 68L73 69L70 69L70 70L72 70L72 72L71 72L71 71L70 71L70 72L71 72L71 74L70 74L70 73L69 73L69 75L68 75L68 76L67 76L67 77L69 77L69 75L71 75L71 76L70 76L70 77L72 77L72 76L74 76L74 77L73 77L73 78L71 78L71 80L70 80L70 81L71 81L71 80L72 80L72 82L74 82L74 84L76 84L76 85L78 85L78 86L77 86L77 87L79 87L79 86L80 86L80 85L78 85L78 84L76 84L76 82L77 82L77 83L80 83L80 84L81 84L81 86L82 86L82 82L83 82L83 81L82 81L82 79L80 79L80 78L81 78L81 77L82 77L82 78L83 78L83 77L84 77L84 76L86 76L86 75L87 75L87 76L88 76L88 75L87 75L87 74L86 74L86 72L88 72L88 71L89 71L89 72L90 72L90 74L89 74L89 73L88 73L88 74L89 74L89 77L85 77L85 80L87 80L87 81L85 81L85 82L87 82L87 81L88 81L88 83L87 83L87 88L86 88L86 89L83 89L83 88L84 88L84 87L83 87L83 88L82 88L82 89L83 89L83 91L82 91L82 92L83 92L83 91L84 91L84 92L85 92L85 93L81 93L81 94L82 94L82 96L83 96L83 97L84 97L84 96L83 96L83 95L85 95L85 94L86 94L86 93L87 93L87 92L88 92L88 91L85 91L85 90L86 90L86 89L88 89L88 90L89 90L89 92L91 92L91 93L90 93L90 95L89 95L89 97L92 97L92 96L93 96L93 98L89 98L89 100L90 100L90 99L92 99L92 101L90 101L90 102L89 102L89 101L88 101L88 102L89 102L89 103L92 103L92 104L93 104L93 105L94 105L94 106L95 106L95 105L97 105L97 106L98 106L98 105L97 105L97 103L99 103L99 104L100 104L100 103L99 103L99 102L97 102L97 101L95 101L95 100L97 100L97 99L99 99L99 100L100 100L100 101L102 101L102 99L101 99L101 97L100 97L100 99L99 99L99 97L98 97L98 96L97 96L97 94L95 94L95 93L94 93L94 92L96 92L96 93L97 93L97 91L99 91L99 92L100 92L100 91L101 91L101 92L102 92L102 91L104 91L104 90L105 90L105 89L104 89L104 88L105 88L105 87L104 87L104 88L102 88L102 89L100 89L100 87L103 87L103 86L99 86L99 85L98 85L98 84L97 84L97 83L96 83L96 84L95 84L95 83L94 83L94 82L93 82L93 81L92 81L92 80L94 80L94 78L95 78L95 75L96 75L96 74L95 74L95 73L96 73L96 72L97 72L97 70L98 70L98 71L99 71L99 72L100 72L100 71L101 71L101 72L103 72L103 70L104 70L104 68L100 68L100 67L103 67L103 66L101 66L101 65L102 65L102 64L101 64L101 65L98 65L98 63L99 63L99 64L100 64L100 63L99 63L99 61L100 61L100 60L99 60L99 61L96 61L96 60L97 60L97 59L94 59L94 58L93 58L93 57L95 57L95 58L96 58L96 57L97 57L97 55L96 55L96 54ZM106 54L106 55L105 55L105 56L106 56L106 55L107 55L107 54ZM109 54L109 56L112 56L112 55L110 55L110 54ZM6 55L6 56L7 56L7 55ZM8 55L8 56L9 56L9 55ZM75 55L75 56L76 56L76 55ZM16 56L16 57L17 57L17 56ZM48 56L48 57L49 57L49 56ZM88 56L88 57L89 57L89 56ZM95 56L95 57L96 57L96 56ZM5 57L5 60L8 60L8 57ZM10 57L10 58L11 58L11 61L12 61L12 62L14 62L14 61L15 61L15 60L16 60L16 59L15 59L15 60L14 60L14 58L13 58L13 57L12 57L12 58L11 58L11 57ZM31 57L31 60L34 60L34 57ZM37 57L37 58L35 58L35 61L34 61L34 63L32 63L32 64L31 64L31 63L30 63L30 64L28 64L28 66L29 66L29 67L30 67L30 66L29 66L29 65L31 65L31 66L32 66L32 65L33 65L33 64L34 64L34 65L35 65L35 64L36 64L36 65L37 65L37 64L40 64L40 63L39 63L39 61L37 61L37 60L40 60L40 59L39 59L39 58L38 58L38 57ZM45 57L45 58L46 58L46 57ZM57 57L57 60L60 60L60 57ZM83 57L83 60L86 60L86 57ZM109 57L109 60L112 60L112 57ZM6 58L6 59L7 59L7 58ZM32 58L32 59L33 59L33 58ZM37 58L37 59L38 59L38 58ZM53 58L53 59L54 59L54 60L55 60L55 59L54 59L54 58ZM58 58L58 59L59 59L59 58ZM84 58L84 59L85 59L85 58ZM110 58L110 59L111 59L111 58ZM29 59L29 60L30 60L30 59ZM65 59L65 61L66 61L66 59ZM76 59L76 60L77 60L77 59ZM80 59L80 60L81 60L81 61L82 61L82 63L85 63L85 64L84 64L84 65L86 65L86 63L87 63L87 64L88 64L88 62L89 62L89 63L91 63L91 61L90 61L90 60L88 60L88 59L87 59L87 61L86 61L86 63L85 63L85 62L83 62L83 61L82 61L82 60L81 60L81 59ZM13 60L13 61L14 61L14 60ZM62 60L62 61L63 61L63 60ZM2 61L2 63L5 63L5 62L8 62L8 61L4 61L4 62L3 62L3 61ZM31 61L31 62L32 62L32 61ZM35 61L35 62L36 62L36 63L38 63L38 62L36 62L36 61ZM40 61L40 62L41 62L41 61ZM87 61L87 62L88 62L88 61ZM59 62L59 64L58 64L58 65L57 65L57 66L56 66L56 68L58 68L58 67L57 67L57 66L58 66L58 65L59 65L59 67L60 67L60 65L62 65L62 67L63 67L63 66L65 66L65 67L67 67L67 66L69 66L69 64L68 64L68 65L65 65L65 64L64 64L64 65L62 65L62 64L60 64L60 63L61 63L61 62ZM97 62L97 63L98 63L98 62ZM10 63L10 64L8 64L8 65L10 65L10 66L8 66L8 67L9 67L9 68L11 68L11 69L12 69L12 68L13 68L13 67L12 67L12 66L11 66L11 63ZM46 63L46 64L45 64L45 65L44 65L44 66L45 66L45 65L46 65L46 66L47 66L47 65L46 65L46 64L47 64L47 63ZM59 64L59 65L60 65L60 64ZM0 65L0 68L2 68L2 67L3 67L3 66L4 66L4 65L3 65L3 66L2 66L2 65ZM49 65L49 67L51 67L51 68L50 68L50 69L49 69L49 70L48 70L48 71L47 71L47 72L50 72L50 71L51 71L51 72L52 72L52 71L51 71L51 70L50 70L50 69L52 69L52 68L53 68L53 67L51 67L51 66L50 66L50 65ZM76 65L76 68L77 68L77 69L75 69L75 67L74 67L74 69L73 69L73 71L74 71L74 70L75 70L75 71L76 71L76 72L78 72L78 73L77 73L77 74L78 74L78 73L80 73L80 74L79 74L79 75L78 75L78 76L77 76L77 75L75 75L75 77L74 77L74 78L73 78L73 79L74 79L74 80L75 80L75 81L74 81L74 82L76 82L76 81L80 81L80 82L82 82L82 81L81 81L81 80L80 80L80 79L76 79L76 78L75 78L75 77L77 77L77 78L80 78L80 75L82 75L82 77L83 77L83 75L84 75L84 74L85 74L85 75L86 75L86 74L85 74L85 70L86 70L86 71L88 71L88 68L89 68L89 69L90 69L90 70L91 70L91 69L93 69L93 70L92 70L92 71L91 71L91 72L92 72L92 71L93 71L93 74L92 74L92 73L91 73L91 75L93 75L93 76L94 76L94 75L95 75L95 74L94 74L94 72L95 72L95 71L96 71L96 70L97 70L97 69L98 69L98 70L99 70L99 71L100 71L100 70L101 70L101 69L100 69L100 68L99 68L99 67L98 67L98 65L97 65L97 66L96 66L96 67L95 67L95 66L94 66L94 67L93 67L93 68L92 68L92 67L90 67L90 66L89 66L89 67L87 67L87 68L86 68L86 69L83 69L83 71L82 71L82 68L83 68L83 67L82 67L82 68L80 68L80 67L78 67L78 68L77 68L77 65ZM1 66L1 67L2 67L2 66ZM16 66L16 67L15 67L15 68L16 68L16 70L18 70L18 68L19 68L19 69L20 69L20 67L18 67L18 66ZM16 67L16 68L18 68L18 67ZM89 67L89 68L90 68L90 67ZM96 67L96 69L97 69L97 68L98 68L98 67ZM6 68L6 69L8 69L8 68ZM22 68L22 69L23 69L23 70L24 70L24 69L23 69L23 68ZM116 68L116 69L117 69L117 68ZM31 69L31 70L32 70L32 69ZM61 69L61 70L62 70L62 69ZM79 69L79 70L77 70L77 71L78 71L78 72L79 72L79 70L80 70L80 72L81 72L81 73L82 73L82 74L83 74L83 73L84 73L84 72L83 72L83 73L82 73L82 72L81 72L81 70L80 70L80 69ZM86 69L86 70L87 70L87 69ZM94 69L94 70L93 70L93 71L94 71L94 70L95 70L95 69ZM1 70L1 71L3 71L3 70ZM26 70L26 75L27 75L27 76L28 76L28 75L27 75L27 73L28 73L28 71L27 71L27 70ZM45 70L45 71L44 71L44 72L46 72L46 70ZM49 70L49 71L50 71L50 70ZM39 71L39 72L40 72L40 71ZM3 72L3 73L4 73L4 76L5 76L5 77L6 77L6 76L7 76L7 75L5 75L5 72ZM10 72L10 73L11 73L11 72ZM58 72L58 73L59 73L59 74L60 74L60 75L61 75L61 74L62 74L62 75L63 75L63 76L64 76L64 75L63 75L63 74L62 74L62 73L61 73L61 74L60 74L60 73L59 73L59 72ZM74 72L74 73L72 73L72 74L73 74L73 75L74 75L74 74L76 74L76 73L75 73L75 72ZM12 73L12 74L13 74L13 73ZM24 73L24 75L25 75L25 73ZM46 74L46 75L47 75L47 74ZM93 74L93 75L94 75L94 74ZM111 74L111 75L112 75L112 74ZM13 75L13 76L14 76L14 77L15 77L15 78L16 78L16 77L15 77L15 76L14 76L14 75ZM37 76L37 77L38 77L38 76ZM116 76L116 77L114 77L114 78L115 78L115 79L116 79L116 77L117 77L117 76ZM91 77L91 79L90 79L90 80L91 80L91 79L93 79L93 78L94 78L94 77ZM106 77L106 78L108 78L108 77ZM39 78L39 79L40 79L40 80L37 80L37 81L36 81L36 82L37 82L37 83L38 83L38 84L39 84L39 85L41 85L41 86L40 86L40 90L39 90L39 91L38 91L38 90L37 90L37 93L36 93L36 94L35 94L35 95L36 95L36 94L37 94L37 93L39 93L39 95L40 95L40 96L41 96L41 95L42 95L42 94L43 94L43 95L45 95L45 94L43 94L43 93L45 93L45 92L44 92L44 90L46 90L46 92L47 92L47 91L50 91L50 89L52 89L52 88L51 88L51 87L49 87L49 88L50 88L50 89L49 89L49 90L46 90L46 89L45 89L45 88L47 88L47 87L45 87L45 85L46 85L46 86L47 86L47 84L45 84L45 85L44 85L44 84L43 84L43 85L44 85L44 86L43 86L43 87L42 87L42 83L40 83L40 84L39 84L39 83L38 83L38 82L37 82L37 81L39 81L39 82L40 82L40 81L41 81L41 82L43 82L43 83L45 83L45 82L44 82L44 79L41 79L41 78ZM59 78L59 79L60 79L60 78ZM69 78L69 79L70 79L70 78ZM74 78L74 79L75 79L75 80L76 80L76 79L75 79L75 78ZM86 78L86 79L88 79L88 80L89 80L89 78ZM103 78L103 81L104 81L104 78ZM27 79L27 80L26 80L26 84L27 84L27 85L28 85L28 86L29 86L29 87L30 87L30 82L29 82L29 81L27 81L27 80L28 80L28 79ZM46 80L46 81L47 81L47 80ZM105 80L105 81L106 81L106 80ZM108 80L108 81L109 81L109 82L110 82L110 80ZM6 81L6 82L8 82L8 81ZM54 81L54 82L55 82L55 81ZM89 81L89 82L92 82L92 83L88 83L88 85L89 85L89 86L90 86L90 84L92 84L92 85L91 85L91 86L94 86L94 87L93 87L93 88L92 88L92 87L88 87L88 89L89 89L89 90L91 90L91 92L92 92L92 93L93 93L93 94L91 94L91 95L90 95L90 96L92 96L92 95L93 95L93 96L94 96L94 97L95 97L95 96L94 96L94 95L95 95L95 94L94 94L94 93L93 93L93 92L92 92L92 90L93 90L93 91L95 91L95 89L96 89L96 91L97 91L97 89L96 89L96 88L95 88L95 86L97 86L97 87L98 87L98 85L95 85L95 84L94 84L94 83L93 83L93 82L92 82L92 81ZM28 82L28 85L29 85L29 82ZM46 82L46 83L48 83L48 82ZM98 82L98 83L99 83L99 82ZM5 83L5 86L8 86L8 83ZM15 83L15 85L16 85L16 87L14 87L14 85L11 85L11 86L12 86L12 87L13 87L13 88L16 88L16 87L17 87L17 86L18 86L18 85L16 85L16 83ZM31 83L31 86L34 86L34 83ZM57 83L57 86L60 86L60 83ZM83 83L83 86L86 86L86 83ZM92 83L92 84L93 84L93 83ZM109 83L109 86L112 86L112 83ZM6 84L6 85L7 85L7 84ZM32 84L32 85L33 85L33 84ZM58 84L58 85L59 85L59 84ZM67 84L67 85L69 85L69 84ZM84 84L84 85L85 85L85 84ZM103 84L103 85L104 85L104 84ZM110 84L110 85L111 85L111 84ZM48 85L48 86L50 86L50 85ZM5 87L5 89L6 89L6 90L7 90L7 91L6 91L6 92L7 92L7 93L6 93L6 94L5 94L5 93L3 93L3 92L5 92L5 90L4 90L4 89L3 89L3 90L4 90L4 91L2 91L2 93L3 93L3 94L5 94L5 96L3 96L3 97L5 97L5 96L8 96L8 94L7 94L7 93L8 93L8 92L9 92L9 94L10 94L10 96L11 96L11 95L12 95L12 96L13 96L13 95L12 95L12 94L10 94L10 92L9 92L9 89L6 89L6 88L7 88L7 87ZM9 87L9 88L10 88L10 87ZM33 87L33 88L34 88L34 87ZM41 87L41 91L39 91L39 93L40 93L40 92L41 92L41 93L42 93L42 92L43 92L43 90L44 90L44 89L43 89L43 90L42 90L42 87ZM43 87L43 88L45 88L45 87ZM113 87L113 88L112 88L112 89L111 89L111 90L112 90L112 89L114 89L114 87ZM18 88L18 89L13 89L13 91L16 91L16 92L17 92L17 91L16 91L16 90L18 90L18 89L19 89L19 88ZM89 88L89 89L91 89L91 90L92 90L92 88ZM93 88L93 89L95 89L95 88ZM98 88L98 89L99 89L99 90L100 90L100 89L99 89L99 88ZM71 90L71 91L72 91L72 90ZM101 90L101 91L102 91L102 90ZM7 91L7 92L8 92L8 91ZM41 91L41 92L42 92L42 91ZM111 91L111 92L113 92L113 91ZM13 92L13 93L14 93L14 92ZM28 92L28 93L29 93L29 92ZM100 93L100 95L99 95L99 94L98 94L98 95L99 95L99 96L100 96L100 95L101 95L101 96L102 96L102 95L103 95L103 94L102 94L102 93ZM6 94L6 95L7 95L7 94ZM29 94L29 95L30 95L30 94ZM40 94L40 95L41 95L41 94ZM47 94L47 96L52 96L52 95L51 95L51 94L50 94L50 95L48 95L48 94ZM62 94L62 95L61 95L61 96L62 96L62 95L63 95L63 94ZM101 94L101 95L102 95L102 94ZM33 95L33 96L34 96L34 95ZM80 95L80 97L81 97L81 95ZM0 96L0 97L1 97L1 96ZM54 96L54 97L56 97L56 98L57 98L57 96ZM58 96L58 98L59 98L59 96ZM96 96L96 97L97 97L97 98L98 98L98 97L97 97L97 96ZM15 97L15 99L16 99L16 97ZM17 97L17 100L18 100L18 101L19 101L19 100L18 100L18 97ZM20 97L20 98L21 98L21 97ZM33 97L33 98L34 98L34 97ZM43 97L43 98L44 98L44 97ZM75 97L75 98L76 98L76 97ZM86 97L86 98L87 98L87 97ZM116 97L116 98L113 98L113 99L112 99L112 100L114 100L114 99L115 99L115 100L116 100L116 98L117 98L117 97ZM0 98L0 99L1 99L1 98ZM94 98L94 99L93 99L93 101L92 101L92 102L94 102L94 103L93 103L93 104L95 104L95 102L94 102L94 99L96 99L96 98ZM12 99L12 101L11 101L11 102L9 102L9 101L6 101L6 102L7 102L7 103L6 103L6 104L8 104L8 102L9 102L9 103L10 103L10 104L12 104L12 103L11 103L11 102L13 102L13 99ZM35 99L35 100L37 100L37 99ZM67 99L67 100L68 100L68 99ZM100 99L100 100L101 100L101 99ZM20 100L20 102L19 102L19 103L17 103L17 104L16 104L16 105L17 105L17 106L16 106L16 107L18 107L18 105L19 105L19 103L20 103L20 102L21 102L21 103L22 103L22 102L21 102L21 100ZM73 100L73 101L72 101L72 103L71 103L71 102L70 102L70 103L69 103L69 104L68 104L68 106L67 106L67 108L68 108L68 109L71 109L71 108L72 108L72 107L73 107L73 109L72 109L72 110L70 110L70 111L69 111L69 112L68 112L68 113L66 113L66 114L70 114L70 113L69 113L69 112L70 112L70 111L71 111L71 114L72 114L72 113L75 113L75 111L76 111L76 112L78 112L78 114L79 114L79 112L80 112L80 111L81 111L81 109L80 109L80 111L76 111L76 109L75 109L75 111L74 111L74 106L75 106L75 105L74 105L74 106L72 106L72 107L71 107L71 106L70 106L70 105L71 105L71 104L72 104L72 103L73 103L73 104L74 104L74 102L75 102L75 103L76 103L76 105L77 105L77 106L76 106L76 108L77 108L77 110L79 110L79 108L80 108L80 107L81 107L81 106L79 106L79 107L78 107L78 108L77 108L77 106L78 106L78 104L77 104L77 103L76 103L76 101L75 101L75 100ZM14 101L14 102L15 102L15 101ZM58 101L58 102L59 102L59 103L61 103L61 101L60 101L60 102L59 102L59 101ZM73 101L73 102L74 102L74 101ZM4 102L4 103L5 103L5 102ZM52 102L52 104L55 104L55 103L53 103L53 102ZM96 102L96 103L97 103L97 102ZM103 103L103 104L104 104L104 103ZM37 104L37 105L38 105L38 104ZM69 104L69 105L70 105L70 104ZM5 105L5 106L7 106L7 107L6 107L6 108L7 108L7 107L8 107L8 108L9 108L9 106L10 106L10 105L9 105L9 106L8 106L8 105ZM34 105L34 106L35 106L35 105ZM87 105L87 106L88 106L88 105ZM69 106L69 107L68 107L68 108L71 108L71 107L70 107L70 106ZM100 106L100 110L99 110L99 111L100 111L100 110L101 110L101 109L103 109L103 112L104 112L104 111L105 111L105 110L104 110L104 109L107 109L107 108L106 108L106 107L105 107L105 106L104 106L104 107L103 107L103 108L102 108L102 107L101 107L101 106ZM37 107L37 108L39 108L39 109L38 109L38 110L39 110L39 109L40 109L40 108L41 108L41 107L40 107L40 108L39 108L39 107ZM104 107L104 108L105 108L105 107ZM31 109L31 112L34 112L34 109ZM57 109L57 112L60 112L60 109ZM83 109L83 112L86 112L86 109ZM88 109L88 111L87 111L87 112L88 112L88 111L91 111L91 110L89 110L89 109ZM109 109L109 112L112 112L112 109ZM115 109L115 110L116 110L116 109ZM32 110L32 111L33 111L33 110ZM58 110L58 111L59 111L59 110ZM84 110L84 111L85 111L85 110ZM106 110L106 111L107 111L107 110ZM110 110L110 111L111 111L111 110ZM18 111L18 113L19 113L19 112L20 112L20 111ZM72 111L72 112L73 112L73 111ZM21 113L21 114L22 114L22 113ZM48 113L48 115L50 115L50 114L49 114L49 113ZM105 113L105 115L104 115L104 114L102 114L102 115L101 115L101 116L102 116L102 115L104 115L104 116L105 116L105 115L106 115L106 113ZM115 113L115 114L117 114L117 113ZM53 114L53 115L54 115L54 116L55 116L55 115L56 115L56 114ZM11 115L11 116L12 116L12 115ZM19 115L19 116L20 116L20 117L21 117L21 116L20 116L20 115ZM27 115L27 116L29 116L29 117L31 117L31 116L32 116L32 115L31 115L31 116L30 116L30 115ZM40 115L40 116L41 116L41 117L44 117L44 115L43 115L43 116L42 116L42 115ZM116 116L116 117L117 117L117 116ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM117 0L110 0L110 7L117 7ZM116 1L111 1L111 6L116 6ZM115 2L112 2L112 5L115 5ZM0 117L7 117L7 110L0 110ZM1 116L6 116L6 111L1 111ZM2 115L5 115L5 112L2 112Z\" fill=\"#000000\"/></g></g></svg>\n",
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"512\" height=\"512\" viewBox=\"0 0 512 512\"><rect x=\"0\" y=\"0\" width=\"512\" height=\"512\" fill=\"#fefefe\"/><g transform=\"scale(3.436)\"><g transform=\"translate(16.000,16.000)\"><path fill-rule=\"evenodd\" d=\"M13 0L13 1L8 1L8 2L9 2L9 3L8 3L8 4L9 4L9 3L10 3L10 10L11 10L11 11L8 11L8 10L9 10L9 8L6 8L6 9L7 9L7 10L6 10L6 11L8 11L8 13L7 13L7 12L6 12L6 13L4 13L4 14L3 14L3 16L5 16L5 19L4 19L4 21L3 21L3 18L2 18L2 19L0 19L0 20L1 20L1 21L2 21L2 23L3 23L3 24L1 24L1 25L0 25L0 28L1 28L1 27L2 27L2 29L1 29L1 31L0 31L0 40L3 40L3 39L5 39L5 41L4 41L4 42L5 42L5 41L7 41L7 42L6 42L6 43L7 43L7 44L6 44L6 45L8 45L8 42L9 42L9 44L11 44L11 45L9 45L9 46L10 46L10 47L9 47L9 48L6 48L6 47L7 47L7 46L5 46L5 45L4 45L4 44L2 44L2 43L3 43L3 41L2 41L2 43L1 43L1 41L0 41L0 43L1 43L1 44L2 44L2 46L1 46L1 45L0 45L0 49L1 49L1 50L0 50L0 53L1 53L1 54L3 54L3 52L1 52L1 51L4 51L4 52L6 52L6 53L8 53L8 54L4 54L4 55L5 55L5 56L4 56L4 57L3 57L3 56L2 56L2 57L0 57L0 58L2 58L2 57L3 57L3 59L2 59L2 60L1 60L1 59L0 59L0 60L1 60L1 62L0 62L0 63L1 63L1 65L0 65L0 67L1 67L1 66L2 66L2 68L1 68L1 69L0 69L0 70L1 70L1 71L0 71L0 72L2 72L2 70L1 70L1 69L4 69L4 70L3 70L3 71L5 71L5 70L6 70L6 71L9 71L9 72L8 72L8 73L9 73L9 74L6 74L6 73L7 73L7 72L6 72L6 73L5 73L5 72L4 72L4 73L5 73L5 74L6 74L6 75L9 75L9 77L8 77L8 76L6 76L6 77L5 77L5 75L4 75L4 74L3 74L3 73L2 73L2 74L0 74L0 78L1 78L1 79L0 79L0 81L1 81L1 83L0 83L0 84L1 84L1 85L0 85L0 86L1 86L1 88L0 88L0 89L1 89L1 90L0 90L0 92L1 92L1 93L2 93L2 92L3 92L3 95L4 95L4 96L5 96L5 97L3 97L3 96L2 96L2 94L0 94L0 97L2 97L2 98L1 98L1 99L0 99L0 100L2 100L2 101L0 101L0 103L2 103L2 104L3 104L3 105L4 105L4 107L5 107L5 108L6 108L6 109L7 109L7 108L9 108L9 107L7 107L7 106L6 106L6 105L9 105L9 106L11 106L11 105L12 105L12 106L13 106L13 107L14 107L14 105L13 105L13 104L14 104L14 103L15 103L15 101L14 101L14 99L15 99L15 100L16 100L16 102L17 102L17 104L18 104L18 105L19 105L19 106L17 106L17 107L16 107L16 108L17 108L17 107L18 107L18 108L19 108L19 109L17 109L17 110L16 110L16 109L15 109L15 108L14 108L14 109L12 109L12 110L16 110L16 111L11 111L11 110L10 110L10 109L8 109L8 111L9 111L9 112L8 112L8 117L10 117L10 116L12 116L12 117L13 117L13 116L14 116L14 117L17 117L17 116L19 116L19 117L20 117L20 116L21 116L21 114L22 114L22 117L25 117L25 116L26 116L26 114L24 114L24 115L23 115L23 114L22 114L22 113L23 113L23 112L24 112L24 113L26 113L26 112L25 112L25 111L26 111L26 110L27 110L27 109L28 109L28 108L27 108L27 109L25 109L25 111L22 111L22 110L21 110L21 109L20 109L20 108L22 108L22 109L23 109L23 108L25 108L25 107L26 107L26 105L25 105L25 103L26 103L26 101L25 101L25 99L26 99L26 98L27 98L27 97L26 97L26 96L27 96L27 95L29 95L29 94L28 94L28 93L27 93L27 92L26 92L26 91L25 91L25 90L26 90L26 89L25 89L25 88L27 88L27 87L28 87L28 85L29 85L29 86L30 86L30 87L31 87L31 88L28 88L28 89L27 89L27 90L28 90L28 91L29 91L29 90L28 90L28 89L31 89L31 88L32 88L32 87L33 87L33 88L34 88L34 87L35 87L35 90L34 90L34 89L33 89L33 90L34 90L34 92L37 92L37 93L38 93L38 92L39 92L39 93L40 93L40 94L39 94L39 95L41 95L41 96L39 96L39 97L36 97L36 98L39 98L39 99L35 99L35 101L36 101L36 103L35 103L35 106L33 106L33 108L32 108L32 106L31 106L31 105L30 105L30 104L29 104L29 105L30 105L30 106L29 106L29 107L30 107L30 106L31 106L31 108L29 108L29 109L30 109L30 113L27 113L27 115L29 115L29 116L30 116L30 115L31 115L31 116L32 116L32 115L34 115L34 117L35 117L35 116L36 116L36 117L38 117L38 116L37 116L37 115L38 115L38 113L35 113L35 111L36 111L36 112L38 112L38 110L40 110L40 111L39 111L39 112L40 112L40 111L43 111L43 112L44 112L44 111L46 111L46 112L48 112L48 113L45 113L45 114L44 114L44 115L43 115L43 114L41 114L41 113L39 113L39 115L43 115L43 116L42 116L42 117L43 117L43 116L44 116L44 115L45 115L45 117L48 117L48 116L49 116L49 115L50 115L50 116L52 116L52 115L50 115L50 114L51 114L51 113L49 113L49 111L50 111L50 110L49 110L49 111L46 111L46 110L48 110L48 109L47 109L47 108L48 108L48 107L49 107L49 109L50 109L50 108L52 108L52 109L53 109L53 112L56 112L56 113L55 113L55 114L53 114L53 116L54 116L54 117L56 117L56 114L58 114L58 113L60 113L60 114L59 114L59 115L57 115L57 116L58 116L58 117L60 117L60 116L61 116L61 115L60 115L60 114L61 114L61 112L62 112L62 110L63 110L63 111L64 111L64 110L65 110L65 112L64 112L64 113L63 113L63 114L64 114L64 115L65 115L65 116L66 116L66 117L69 117L69 116L70 116L70 115L69 115L69 116L68 116L68 115L67 115L67 113L68 113L68 112L67 112L67 111L68 111L68 110L69 110L69 109L68 109L68 110L66 110L66 109L64 109L64 107L67 107L67 108L68 108L68 106L65 106L65 104L66 104L66 105L68 105L68 102L66 102L66 101L67 101L67 100L68 100L68 101L69 101L69 100L68 100L68 99L70 99L70 102L71 102L71 103L69 103L69 104L72 104L72 107L71 107L71 108L73 108L73 107L74 107L74 105L73 105L73 103L74 103L74 101L76 101L76 99L77 99L77 101L78 101L78 102L77 102L77 103L78 103L78 105L80 105L80 103L81 103L81 104L82 104L82 106L81 106L81 108L82 108L82 110L81 110L81 109L80 109L80 106L77 106L77 107L78 107L78 108L79 108L79 109L78 109L78 110L77 110L77 109L75 109L75 108L74 108L74 109L75 109L75 111L76 111L76 110L77 110L77 111L79 111L79 112L80 112L80 113L81 113L81 112L82 112L82 113L83 113L83 115L82 115L82 114L80 114L80 115L79 115L79 114L78 114L78 115L79 115L79 116L80 116L80 115L82 115L82 116L81 116L81 117L84 117L84 116L85 116L85 115L86 115L86 114L87 114L87 112L88 112L88 111L90 111L90 112L89 112L89 114L90 114L90 115L89 115L89 117L91 117L91 116L92 116L92 114L96 114L96 115L95 115L95 116L93 116L93 117L97 117L97 116L98 116L98 114L100 114L100 115L101 115L101 116L99 116L99 117L101 117L101 116L102 116L102 117L104 117L104 115L105 115L105 117L109 117L109 116L110 116L110 117L114 117L114 116L115 116L115 117L116 117L116 116L115 116L115 115L114 115L114 114L115 114L115 113L116 113L116 112L117 112L117 111L116 111L116 110L117 110L117 107L116 107L116 106L115 106L115 105L117 105L117 103L116 103L116 102L115 102L115 101L114 101L114 100L115 100L115 99L116 99L116 97L117 97L117 96L116 96L116 94L115 94L115 93L114 93L114 94L115 94L115 95L111 95L111 97L112 97L112 96L113 96L113 98L111 98L111 100L112 100L112 99L113 99L113 101L114 101L114 102L115 102L115 103L113 103L113 104L112 104L112 105L114 105L114 106L115 106L115 107L113 107L113 108L112 108L112 107L109 107L109 105L111 105L111 103L110 103L110 102L111 102L111 101L110 101L110 102L109 102L109 100L108 100L108 99L110 99L110 94L111 94L111 93L113 93L113 92L117 92L117 91L116 91L116 90L117 90L117 88L116 88L116 86L117 86L117 85L115 85L115 86L113 86L113 85L114 85L114 84L116 84L116 77L117 77L117 76L116 76L116 77L115 77L115 75L116 75L116 74L115 74L115 73L116 73L116 72L115 72L115 71L114 71L114 70L113 70L113 71L111 71L111 70L112 70L112 68L113 68L113 69L114 69L114 68L115 68L115 70L117 70L117 69L116 69L116 68L115 68L115 67L117 67L117 66L115 66L115 65L116 65L116 64L117 64L117 63L116 63L116 62L115 62L115 63L114 63L114 64L113 64L113 66L112 66L112 65L111 65L111 64L112 64L112 62L113 62L113 61L114 61L114 60L115 60L115 59L114 59L114 60L113 60L113 56L115 56L115 55L116 55L116 54L117 54L117 53L116 53L116 50L117 50L117 49L116 49L116 48L117 48L117 47L116 47L116 46L117 46L117 45L116 45L116 46L115 46L115 45L114 45L114 44L117 44L117 43L115 43L115 42L117 42L117 39L116 39L116 37L113 37L113 36L116 36L116 34L113 34L113 32L116 32L116 33L117 33L117 32L116 32L116 31L115 31L115 30L117 30L117 29L116 29L116 28L115 28L115 27L116 27L116 26L115 26L115 25L113 25L113 26L115 26L115 27L111 27L111 28L109 28L109 27L108 27L108 28L107 28L107 27L105 27L105 26L102 26L102 25L104 25L104 23L103 23L103 22L102 22L102 21L103 21L103 20L104 20L104 21L105 21L105 20L104 20L104 18L105 18L105 19L106 19L106 22L107 22L107 23L106 23L106 24L105 24L105 25L106 25L106 26L109 26L109 25L110 25L110 23L109 23L109 24L107 24L107 23L108 23L108 22L107 22L107 21L109 21L109 22L110 22L110 21L112 21L112 22L111 22L111 24L116 24L116 23L112 23L112 22L115 22L115 21L114 21L114 20L116 20L116 18L115 18L115 17L117 17L117 15L116 15L116 14L114 14L114 13L112 13L112 12L111 12L111 13L112 13L112 14L110 14L110 15L109 15L109 16L108 16L108 15L105 15L105 14L106 14L106 13L104 13L104 7L105 7L105 8L108 8L108 9L105 9L105 10L107 10L107 13L109 13L109 12L110 12L110 11L111 11L111 9L112 9L112 8L111 8L111 9L110 9L110 8L108 8L108 7L109 7L109 6L108 6L108 5L107 5L107 4L105 4L105 3L104 3L104 2L103 2L103 1L107 1L107 0L102 0L102 2L101 2L101 0L99 0L99 1L100 1L100 2L101 2L101 6L100 6L100 5L99 5L99 6L98 6L98 7L97 7L97 5L98 5L98 4L99 4L99 3L98 3L98 0L92 0L92 1L90 1L90 2L91 2L91 3L87 3L87 4L86 4L86 3L85 3L85 2L86 2L86 1L87 1L87 2L88 2L88 0L86 0L86 1L85 1L85 0L80 0L80 1L83 1L83 3L84 3L84 4L81 4L81 5L80 5L80 4L79 4L79 3L80 3L80 2L79 2L79 0L78 0L78 1L76 1L76 0L73 0L73 1L72 1L72 2L71 2L71 3L69 3L69 4L71 4L71 3L72 3L72 4L73 4L73 6L72 6L72 7L71 7L71 6L70 6L70 8L68 8L68 7L69 7L69 5L68 5L68 7L67 7L67 4L68 4L68 1L69 1L69 0L68 0L68 1L67 1L67 0L64 0L64 1L63 1L63 0L61 0L61 2L60 2L60 1L59 1L59 3L60 3L60 4L56 4L56 5L51 5L51 4L53 4L53 3L54 3L54 2L55 2L55 3L56 3L56 2L57 2L57 1L58 1L58 0L57 0L57 1L56 1L56 0L55 0L55 1L54 1L54 0L51 0L51 1L52 1L52 2L51 2L51 3L50 3L50 1L49 1L49 0L47 0L47 2L48 2L48 3L49 3L49 5L48 5L48 7L47 7L47 6L46 6L46 7L45 7L45 6L44 6L44 7L43 7L43 4L44 4L44 3L43 3L43 2L44 2L44 0L43 0L43 1L41 1L41 2L42 2L42 3L41 3L41 4L39 4L39 2L40 2L40 1L39 1L39 0L38 0L38 1L37 1L37 0L35 0L35 1L36 1L36 2L33 2L33 4L31 4L31 3L32 3L32 2L31 2L31 1L30 1L30 0L29 0L29 2L30 2L30 3L27 3L27 2L26 2L26 3L25 3L25 2L24 2L24 1L26 1L26 0L24 0L24 1L23 1L23 0L22 0L22 1L21 1L21 0L19 0L19 1L17 1L17 0ZM27 0L27 1L28 1L28 0ZM45 0L45 1L46 1L46 0ZM13 1L13 2L12 2L12 3L11 3L11 2L10 2L10 3L11 3L11 4L12 4L12 3L13 3L13 2L14 2L14 3L15 3L15 5L14 5L14 6L13 6L13 5L12 5L12 6L11 6L11 7L12 7L12 6L13 6L13 8L12 8L12 9L11 9L11 10L12 10L12 11L11 11L11 12L9 12L9 14L8 14L8 15L6 15L6 16L7 16L7 17L6 17L6 18L7 18L7 17L8 17L8 15L9 15L9 17L10 17L10 20L9 20L9 21L10 21L10 20L11 20L11 22L8 22L8 19L5 19L5 21L4 21L4 24L3 24L3 26L4 26L4 27L3 27L3 29L2 29L2 30L3 30L3 31L4 31L4 30L3 30L3 29L5 29L5 30L8 30L8 29L6 29L6 28L7 28L7 27L8 27L8 28L9 28L9 30L10 30L10 31L9 31L9 34L10 34L10 35L9 35L9 37L6 37L6 36L8 36L8 35L3 35L3 34L4 34L4 32L2 32L2 31L1 31L1 34L2 34L2 35L3 35L3 36L1 36L1 39L3 39L3 38L5 38L5 39L6 39L6 40L8 40L8 41L9 41L9 42L11 42L11 43L12 43L12 44L13 44L13 46L14 46L14 47L12 47L12 45L11 45L11 47L10 47L10 48L9 48L9 49L13 49L13 48L14 48L14 47L15 47L15 49L16 49L16 48L17 48L17 47L18 47L18 48L19 48L19 49L18 49L18 50L16 50L16 51L15 51L15 50L13 50L13 52L12 52L12 51L10 51L10 50L9 50L9 51L10 51L10 52L11 52L11 53L9 53L9 52L8 52L8 51L6 51L6 52L8 52L8 53L9 53L9 54L8 54L8 55L10 55L10 56L9 56L9 57L11 57L11 56L12 56L12 59L11 59L11 61L9 61L9 62L8 62L8 61L5 61L5 62L4 62L4 63L3 63L3 64L2 64L2 65L3 65L3 64L5 64L5 68L7 68L7 67L6 67L6 66L7 66L7 65L6 65L6 64L8 64L8 65L9 65L9 64L8 64L8 63L11 63L11 61L12 61L12 62L13 62L13 61L14 61L14 64L15 64L15 62L16 62L16 63L18 63L18 65L17 65L17 66L16 66L16 65L14 65L14 67L13 67L13 65L12 65L12 66L11 66L11 65L10 65L10 66L8 66L8 70L9 70L9 71L10 71L10 72L9 72L9 73L10 73L10 72L13 72L13 73L11 73L11 75L10 75L10 74L9 74L9 75L10 75L10 76L12 76L12 77L13 77L13 76L14 76L14 77L15 77L15 78L12 78L12 79L16 79L16 80L15 80L15 81L12 81L12 80L10 80L10 81L12 81L12 82L15 82L15 84L14 84L14 85L15 85L15 86L14 86L14 87L13 87L13 88L15 88L15 89L12 89L12 87L11 87L11 85L9 85L9 86L10 86L10 87L11 87L11 89L12 89L12 90L11 90L11 91L10 91L10 89L9 89L9 87L8 87L8 89L6 89L6 90L3 90L3 89L2 89L2 88L3 88L3 87L4 87L4 85L3 85L3 84L4 84L4 82L5 82L5 81L6 81L6 82L9 82L9 84L10 84L10 82L9 82L9 80L7 80L7 79L6 79L6 80L5 80L5 79L4 79L4 81L2 81L2 79L3 79L3 78L4 78L4 77L3 77L3 78L2 78L2 76L4 76L4 75L1 75L1 78L2 78L2 79L1 79L1 81L2 81L2 83L1 83L1 84L2 84L2 85L3 85L3 87L2 87L2 88L1 88L1 89L2 89L2 90L1 90L1 91L2 91L2 90L3 90L3 91L4 91L4 93L5 93L5 95L6 95L6 96L7 96L7 95L8 95L8 96L9 96L9 97L10 97L10 96L9 96L9 95L8 95L8 94L9 94L9 93L10 93L10 94L16 94L16 97L13 97L13 96L15 96L15 95L11 95L11 97L13 97L13 98L16 98L16 99L17 99L17 101L18 101L18 104L19 104L19 105L20 105L20 106L19 106L19 107L20 107L20 106L22 106L22 108L23 108L23 107L24 107L24 106L25 106L25 105L23 105L23 106L22 106L22 104L23 104L23 103L22 103L22 104L21 104L21 103L20 103L20 102L19 102L19 100L20 100L20 101L21 101L21 102L23 102L23 101L21 101L21 100L23 100L23 98L22 98L22 97L24 97L24 98L25 98L25 95L24 95L24 94L23 94L23 93L22 93L22 94L21 94L21 95L20 95L20 94L18 94L18 93L16 93L16 92L17 92L17 91L19 91L19 92L20 92L20 91L21 91L21 92L22 92L22 90L20 90L20 89L23 89L23 91L24 91L24 89L23 89L23 87L27 87L27 86L26 86L26 85L27 85L27 84L30 84L30 83L28 83L28 82L29 82L29 80L30 80L30 78L33 78L33 77L34 77L34 78L35 78L35 77L34 77L34 76L33 76L33 75L34 75L34 73L35 73L35 75L37 75L37 74L38 74L38 75L39 75L39 76L40 76L40 77L39 77L39 78L38 78L38 79L37 79L37 80L35 80L35 79L33 79L33 80L34 80L34 81L31 81L31 82L34 82L34 81L35 81L35 82L37 82L37 81L38 81L38 83L37 83L37 86L36 86L36 85L35 85L35 87L37 87L37 86L38 86L38 88L36 88L36 89L37 89L37 90L35 90L35 91L37 91L37 92L38 92L38 91L39 91L39 92L40 92L40 91L41 91L41 93L42 93L42 92L43 92L43 91L44 91L44 92L46 92L46 91L47 91L47 90L46 90L46 88L47 88L47 89L48 89L48 88L47 88L47 87L49 87L49 88L50 88L50 89L49 89L49 90L48 90L48 91L49 91L49 90L50 90L50 92L53 92L53 93L55 93L55 95L54 95L54 97L53 97L53 95L52 95L52 94L51 94L51 93L50 93L50 94L48 94L48 95L52 95L52 98L55 98L55 99L54 99L54 100L53 100L53 99L52 99L52 100L53 100L53 101L54 101L54 100L55 100L55 102L57 102L57 103L56 103L56 104L57 104L57 105L55 105L55 106L56 106L56 107L55 107L55 109L56 109L56 108L57 108L57 106L58 106L58 108L59 108L59 105L60 105L60 106L61 106L61 107L60 107L60 108L61 108L61 110L62 110L62 109L63 109L63 108L61 108L61 107L64 107L64 105L63 105L63 104L64 104L64 103L66 103L66 104L67 104L67 103L66 103L66 102L64 102L64 103L63 103L63 101L64 101L64 100L65 100L65 101L66 101L66 100L67 100L67 99L68 99L68 98L70 98L70 99L72 99L72 98L71 98L71 97L73 97L73 98L74 98L74 100L73 100L73 101L71 101L71 102L72 102L72 103L73 103L73 101L74 101L74 100L75 100L75 99L76 99L76 98L79 98L79 97L80 97L80 100L79 100L79 102L82 102L82 104L83 104L83 105L84 105L84 106L82 106L82 107L83 107L83 108L87 108L87 109L88 109L88 110L87 110L87 111L88 111L88 110L91 110L91 109L88 109L88 108L89 108L89 107L84 107L84 106L87 106L87 105L88 105L88 106L89 106L89 105L91 105L91 104L90 104L90 103L88 103L88 102L89 102L89 101L90 101L90 102L91 102L91 103L92 103L92 104L94 104L94 105L92 105L92 106L93 106L93 107L91 107L91 108L93 108L93 109L92 109L92 112L91 112L91 113L90 113L90 114L91 114L91 113L93 113L93 112L95 112L95 113L96 113L96 114L98 114L98 113L97 113L97 112L99 112L99 113L102 113L102 111L101 111L101 110L100 110L100 109L101 109L101 107L100 107L100 106L99 106L99 105L97 105L97 104L98 104L98 103L99 103L99 104L101 104L101 105L102 105L102 106L103 106L103 103L102 103L102 102L101 102L101 101L103 101L103 102L104 102L104 104L105 104L105 103L106 103L106 105L107 105L107 106L104 106L104 108L105 108L105 107L107 107L107 106L108 106L108 104L110 104L110 103L109 103L109 102L108 102L108 100L107 100L107 99L108 99L108 98L109 98L109 96L106 96L106 95L107 95L107 94L108 94L108 95L109 95L109 94L108 94L108 93L111 93L111 92L112 92L112 91L114 91L114 89L113 89L113 88L112 88L112 87L108 87L108 84L107 84L107 83L108 83L108 82L106 82L106 81L104 81L104 80L105 80L105 79L106 79L106 80L107 80L107 78L106 78L106 77L107 77L107 76L106 76L106 77L105 77L105 75L107 75L107 73L108 73L108 71L109 71L109 74L111 74L111 73L112 73L112 74L114 74L114 73L113 73L113 72L110 72L110 71L109 71L109 70L111 70L111 68L112 68L112 66L111 66L111 65L110 65L110 63L111 63L111 61L110 61L110 62L108 62L108 61L105 61L105 60L106 60L106 59L108 59L108 56L109 56L109 55L110 55L110 56L113 56L113 55L115 55L115 54L113 54L113 55L112 55L112 54L109 54L109 50L108 50L108 49L109 49L109 48L108 48L108 47L110 47L110 49L111 49L111 48L113 48L113 44L114 44L114 43L113 43L113 42L112 42L112 41L114 41L114 42L115 42L115 41L116 41L116 39L115 39L115 41L114 41L114 40L113 40L113 37L112 37L112 36L113 36L113 35L111 35L111 36L110 36L110 35L109 35L109 36L110 36L110 39L109 39L109 38L107 38L107 36L108 36L108 34L107 34L107 32L108 32L108 30L106 30L106 31L104 31L104 33L105 33L105 34L104 34L104 36L103 36L103 35L100 35L100 33L101 33L101 34L103 34L103 33L101 33L101 29L102 29L102 28L103 28L103 30L105 30L105 29L107 29L107 28L105 28L105 27L104 27L104 28L103 28L103 27L99 27L99 26L101 26L101 25L102 25L102 24L103 24L103 23L102 23L102 22L101 22L101 21L100 21L100 22L101 22L101 23L99 23L99 20L101 20L101 19L99 19L99 18L98 18L98 17L101 17L101 18L102 18L102 17L103 17L103 16L104 16L104 17L106 17L106 19L107 19L107 20L108 20L108 18L107 18L107 17L108 17L108 16L105 16L105 15L102 15L102 14L104 14L104 13L102 13L102 14L101 14L101 13L99 13L99 11L100 11L100 12L101 12L101 11L100 11L100 10L103 10L103 9L102 9L102 8L101 8L101 7L102 7L102 6L101 6L101 7L100 7L100 6L99 6L99 8L101 8L101 9L98 9L98 8L96 8L96 11L97 11L97 12L96 12L96 13L95 13L95 12L94 12L94 11L95 11L95 10L93 10L93 11L92 11L92 12L93 12L93 13L91 13L91 11L87 11L87 10L90 10L90 9L91 9L91 10L92 10L92 9L93 9L93 8L94 8L94 9L95 9L95 8L94 8L94 6L95 6L95 7L96 7L96 5L97 5L97 4L98 4L98 3L95 3L95 2L96 2L96 1L93 1L93 2L92 2L92 3L91 3L91 5L93 5L93 7L92 7L92 6L91 6L91 7L90 7L90 4L89 4L89 7L88 7L88 5L87 5L87 7L88 7L88 8L87 8L87 10L86 10L86 13L85 13L85 12L83 12L83 11L84 11L84 10L83 10L83 9L82 9L82 8L81 8L81 7L82 7L82 5L81 5L81 7L80 7L80 5L79 5L79 7L78 7L78 6L77 6L77 5L78 5L78 4L77 4L77 2L75 2L75 1L74 1L74 2L73 2L73 4L74 4L74 6L73 6L73 7L74 7L74 6L75 6L75 9L76 9L76 10L74 10L74 11L73 11L73 8L72 8L72 11L71 11L71 10L70 10L70 11L68 11L68 10L69 10L69 9L68 9L68 8L67 8L67 7L66 7L66 5L65 5L65 2L63 2L63 1L62 1L62 3L61 3L61 4L62 4L62 6L61 6L61 8L62 8L62 11L63 11L63 13L62 13L62 12L61 12L61 13L62 13L62 14L60 14L60 15L63 15L63 16L61 16L61 17L60 17L60 19L61 19L61 20L60 20L60 21L61 21L61 22L60 22L60 23L63 23L63 24L61 24L61 25L59 25L59 22L58 22L58 20L57 20L57 19L59 19L59 14L57 14L57 11L58 11L58 12L59 12L59 13L60 13L60 12L59 12L59 11L60 11L60 10L61 10L61 9L60 9L60 10L57 10L57 9L56 9L56 6L55 6L55 7L54 7L54 6L53 6L53 7L52 7L52 6L51 6L51 5L49 5L49 7L50 7L50 6L51 6L51 8L50 8L50 9L49 9L49 8L48 8L48 9L47 9L47 8L45 8L45 7L44 7L44 10L43 10L43 9L42 9L42 8L41 8L41 7L42 7L42 6L41 6L41 5L39 5L39 7L38 7L38 6L37 6L37 5L38 5L38 4L37 4L37 3L38 3L38 2L39 2L39 1L38 1L38 2L37 2L37 3L35 3L35 5L36 5L36 6L35 6L35 7L36 7L36 6L37 6L37 10L38 10L38 11L37 11L37 12L36 12L36 14L34 14L34 13L35 13L35 9L33 9L33 11L32 11L32 14L31 14L31 13L29 13L29 11L28 11L28 10L29 10L29 8L30 8L30 4L28 4L28 5L26 5L26 4L27 4L27 3L26 3L26 4L24 4L24 3L23 3L23 4L21 4L21 3L20 3L20 4L19 4L19 5L18 5L18 3L19 3L19 2L20 2L20 1L19 1L19 2L18 2L18 3L15 3L15 1ZM48 1L48 2L49 2L49 1ZM53 1L53 2L52 2L52 3L53 3L53 2L54 2L54 1ZM108 1L108 2L106 2L106 3L109 3L109 1ZM102 2L102 3L103 3L103 2ZM45 3L45 4L46 4L46 3ZM62 3L62 4L63 4L63 6L62 6L62 8L63 8L63 9L64 9L64 10L65 10L65 12L64 12L64 13L63 13L63 14L64 14L64 15L66 15L66 16L67 16L67 17L68 17L68 16L67 16L67 15L68 15L68 11L67 11L67 10L68 10L68 9L67 9L67 10L66 10L66 7L65 7L65 6L64 6L64 3ZM66 3L66 4L67 4L67 3ZM74 3L74 4L76 4L76 3ZM92 3L92 4L93 4L93 3ZM102 4L102 5L103 5L103 7L104 7L104 5L105 5L105 7L106 7L106 5L105 5L105 4ZM8 5L8 7L9 7L9 5ZM17 5L17 6L16 6L16 7L15 7L15 6L14 6L14 7L15 7L15 8L13 8L13 9L16 9L16 10L17 10L17 12L16 12L16 11L15 11L15 10L14 10L14 13L12 13L12 12L11 12L11 13L10 13L10 14L9 14L9 15L11 15L11 16L10 16L10 17L11 17L11 18L15 18L15 17L14 17L14 16L16 16L16 17L17 17L17 18L16 18L16 19L14 19L14 20L12 20L12 23L11 23L11 24L13 24L13 21L17 21L17 22L16 22L16 23L15 23L15 22L14 22L14 24L16 24L16 23L19 23L19 22L20 22L20 24L21 24L21 25L20 25L20 28L17 28L17 27L18 27L18 25L19 25L19 24L18 24L18 25L17 25L17 27L15 27L15 25L14 25L14 26L11 26L11 25L9 25L9 24L10 24L10 23L7 23L7 22L6 22L6 23L5 23L5 24L4 24L4 25L5 25L5 24L6 24L6 25L9 25L9 26L8 26L8 27L9 27L9 26L11 26L11 32L10 32L10 33L11 33L11 34L12 34L12 35L15 35L15 37L17 37L17 39L16 39L16 38L12 38L12 37L14 37L14 36L12 36L12 37L11 37L11 38L10 38L10 37L9 37L9 38L8 38L8 39L9 39L9 38L10 38L10 40L9 40L9 41L13 41L13 42L14 42L14 44L17 44L17 45L18 45L18 43L15 43L15 42L19 42L19 40L20 40L20 43L19 43L19 44L20 44L20 45L21 45L21 44L24 44L24 45L25 45L25 44L24 44L24 43L27 43L27 40L28 40L28 42L29 42L29 41L30 41L30 39L29 39L29 38L31 38L31 39L33 39L33 37L34 37L34 38L35 38L35 40L33 40L33 41L36 41L36 42L37 42L37 43L35 43L35 44L33 44L33 45L32 45L32 44L31 44L31 45L32 45L32 46L34 46L34 45L35 45L35 44L36 44L36 45L37 45L37 46L36 46L36 47L35 47L35 48L34 48L34 47L32 47L32 48L30 48L30 47L29 47L29 46L28 46L28 45L29 45L29 44L30 44L30 43L29 43L29 44L27 44L27 46L28 46L28 47L29 47L29 48L30 48L30 49L27 49L27 48L25 48L25 49L24 49L24 50L23 50L23 49L22 49L22 50L21 50L21 49L20 49L20 50L21 50L21 51L22 51L22 52L21 52L21 53L19 53L19 54L18 54L18 53L17 53L17 54L16 54L16 52L15 52L15 51L14 51L14 52L13 52L13 53L14 53L14 52L15 52L15 55L16 55L16 56L14 56L14 55L13 55L13 54L12 54L12 53L11 53L11 54L10 54L10 55L13 55L13 57L14 57L14 58L16 58L16 62L18 62L18 61L17 61L17 58L18 58L18 57L19 57L19 58L20 58L20 57L22 57L22 52L24 52L24 55L25 55L25 56L23 56L23 58L21 58L21 59L20 59L20 60L19 60L19 64L21 64L21 65L20 65L20 67L21 67L21 66L22 66L22 68L16 68L16 69L17 69L17 70L16 70L16 72L15 72L15 69L14 69L14 68L13 68L13 67L12 67L12 68L13 68L13 70L14 70L14 73L13 73L13 74L12 74L12 76L13 76L13 74L15 74L15 73L18 73L18 72L19 72L19 73L21 73L21 72L23 72L23 74L22 74L22 75L21 75L21 74L20 74L20 75L19 75L19 74L18 74L18 75L15 75L15 76L19 76L19 77L18 77L18 78L17 78L17 77L16 77L16 79L17 79L17 80L16 80L16 81L15 81L15 82L16 82L16 83L17 83L17 82L18 82L18 83L19 83L19 82L21 82L21 81L22 81L22 85L24 85L24 86L25 86L25 85L26 85L26 84L25 84L25 83L26 83L26 80L27 80L27 79L29 79L29 78L30 78L30 77L29 77L29 78L28 78L28 77L26 77L26 76L27 76L27 75L26 75L26 73L27 73L27 74L28 74L28 75L29 75L29 76L30 76L30 74L32 74L32 73L33 73L33 72L35 72L35 73L36 73L36 72L37 72L37 71L38 71L38 73L39 73L39 75L43 75L43 76L42 76L42 77L41 77L41 80L39 80L39 82L40 82L40 83L38 83L38 84L39 84L39 85L38 85L38 86L39 86L39 85L40 85L40 83L42 83L42 85L43 85L43 86L40 86L40 87L41 87L41 88L39 88L39 89L38 89L38 90L37 90L37 91L38 91L38 90L39 90L39 91L40 91L40 90L41 90L41 91L43 91L43 90L44 90L44 89L45 89L45 88L46 88L46 87L47 87L47 84L49 84L49 85L50 85L50 86L49 86L49 87L50 87L50 88L51 88L51 87L50 87L50 86L52 86L52 87L53 87L53 88L52 88L52 89L51 89L51 91L52 91L52 89L53 89L53 88L54 88L54 87L55 87L55 85L56 85L56 87L57 87L57 88L56 88L56 90L54 90L54 91L53 91L53 92L54 92L54 91L55 91L55 92L56 92L56 94L57 94L57 95L56 95L56 96L55 96L55 97L56 97L56 98L57 98L57 95L58 95L58 96L59 96L59 95L60 95L60 96L61 96L61 95L62 95L62 96L63 96L63 97L61 97L61 98L60 98L60 97L59 97L59 98L58 98L58 99L57 99L57 100L56 100L56 99L55 99L55 100L56 100L56 101L59 101L59 102L58 102L58 104L60 104L60 105L61 105L61 106L62 106L62 104L63 104L63 103L61 103L61 102L62 102L62 100L59 100L59 99L62 99L62 98L63 98L63 99L67 99L67 98L68 98L68 95L69 95L69 96L73 96L73 95L74 95L74 93L75 93L75 94L76 94L76 95L77 95L77 96L74 96L74 97L79 97L79 96L80 96L80 97L81 97L81 98L83 98L83 99L81 99L81 100L82 100L82 101L85 101L85 102L83 102L83 104L84 104L84 105L87 105L87 104L86 104L86 103L85 103L85 102L86 102L86 101L85 101L85 100L86 100L86 99L87 99L87 102L88 102L88 99L89 99L89 100L90 100L90 101L91 101L91 100L92 100L92 99L91 99L91 98L93 98L93 97L92 97L92 95L91 95L91 94L92 94L92 91L91 91L91 90L89 90L89 89L91 89L91 88L92 88L92 90L93 90L93 92L94 92L94 93L97 93L97 95L94 95L94 96L97 96L97 95L98 95L98 97L95 97L95 100L97 100L97 99L98 99L98 100L99 100L99 101L100 101L100 100L99 100L99 99L98 99L98 97L99 97L99 95L101 95L101 93L102 93L102 95L106 95L106 93L107 93L107 92L108 92L108 90L107 90L107 91L106 91L106 90L105 90L105 88L104 88L104 86L107 86L107 85L106 85L106 83L105 83L105 82L104 82L104 81L103 81L103 82L102 82L102 80L101 80L101 84L98 84L98 85L97 85L97 83L98 83L98 81L100 81L100 80L98 80L98 81L97 81L97 82L95 82L95 84L94 84L94 83L93 83L93 82L92 82L92 78L90 78L90 77L92 77L92 75L91 75L91 74L90 74L90 73L87 73L87 75L90 75L90 77L89 77L89 76L88 76L88 77L86 77L86 75L85 75L85 74L86 74L86 72L84 72L84 70L86 70L86 68L87 68L87 70L88 70L88 71L87 71L87 72L88 72L88 71L90 71L90 72L92 72L92 71L93 71L93 72L94 72L94 71L93 71L93 70L92 70L92 69L94 69L94 70L95 70L95 72L96 72L96 74L97 74L97 75L93 75L93 78L94 78L94 77L95 77L95 78L96 78L96 76L97 76L97 75L98 75L98 74L97 74L97 72L98 72L98 73L99 73L99 75L100 75L100 73L101 73L101 76L100 76L100 77L97 77L97 79L94 79L94 80L97 80L97 79L98 79L98 78L99 78L99 79L103 79L103 80L104 80L104 79L103 79L103 78L105 78L105 77L104 77L104 75L105 75L105 74L106 74L106 73L107 73L107 72L106 72L106 73L105 73L105 72L104 72L104 71L103 71L103 70L100 70L100 71L97 71L97 67L98 67L98 70L99 70L99 67L100 67L100 65L98 65L98 64L99 64L99 63L98 63L98 62L97 62L97 63L96 63L96 62L95 62L95 64L94 64L94 63L93 63L93 62L94 62L94 60L95 60L95 59L96 59L96 61L99 61L99 62L100 62L100 63L101 63L101 62L100 62L100 61L101 61L101 60L97 60L97 59L98 59L98 58L97 58L97 59L96 59L96 56L97 56L97 57L98 57L98 56L99 56L99 58L100 58L100 59L103 59L103 60L102 60L102 61L103 61L103 62L102 62L102 63L103 63L103 62L104 62L104 63L106 63L106 64L108 64L108 62L107 62L107 63L106 63L106 62L104 62L104 59L105 59L105 57L104 57L104 56L103 56L103 55L105 55L105 54L104 54L104 53L106 53L106 54L107 54L107 55L109 55L109 54L108 54L108 52L107 52L107 53L106 53L106 52L104 52L104 51L103 51L103 50L104 50L104 49L105 49L105 50L107 50L107 49L108 49L108 48L107 48L107 47L105 47L105 46L104 46L104 45L103 45L103 46L102 46L102 45L101 45L101 47L103 47L103 48L102 48L102 49L103 49L103 50L102 50L102 51L103 51L103 52L102 52L102 54L101 54L101 53L98 53L98 52L99 52L99 51L98 51L98 50L101 50L101 48L99 48L99 47L100 47L100 45L97 45L97 43L98 43L98 44L99 44L99 43L98 43L98 42L99 42L99 41L100 41L100 43L101 43L101 44L102 44L102 43L103 43L103 44L104 44L104 41L105 41L105 40L104 40L104 37L103 37L103 36L102 36L102 37L101 37L101 36L99 36L99 37L98 37L98 38L97 38L97 35L98 35L98 33L97 33L97 34L96 34L96 35L95 35L95 37L96 37L96 38L95 38L95 39L96 39L96 40L94 40L94 39L93 39L93 38L94 38L94 35L93 35L93 34L92 34L92 30L94 30L94 29L95 29L95 28L97 28L97 29L96 29L96 30L95 30L95 31L96 31L96 32L99 32L99 33L100 33L100 32L99 32L99 31L100 31L100 30L99 30L99 31L98 31L98 26L97 26L97 25L96 25L96 26L97 26L97 27L93 27L93 26L95 26L95 24L98 24L98 25L99 25L99 23L98 23L98 20L97 20L97 19L98 19L98 18L97 18L97 17L98 17L98 16L97 16L97 15L98 15L98 14L99 14L99 13L98 13L98 12L97 12L97 13L96 13L96 14L95 14L95 15L94 15L94 16L93 16L93 17L95 17L95 16L97 16L97 17L96 17L96 18L97 18L97 19L95 19L95 20L94 20L94 21L93 21L93 20L92 20L92 19L91 19L91 18L92 18L92 17L91 17L91 15L93 15L93 14L94 14L94 13L93 13L93 14L91 14L91 13L90 13L90 12L89 12L89 14L88 14L88 13L86 13L86 14L87 14L87 15L85 15L85 14L80 14L80 15L79 15L79 14L78 14L78 13L77 13L77 14L75 14L75 15L78 15L78 16L77 16L77 17L78 17L78 18L77 18L77 24L76 24L76 25L75 25L75 26L76 26L76 27L75 27L75 29L74 29L74 28L73 28L73 27L74 27L74 25L73 25L73 26L72 26L72 25L71 25L71 26L72 26L72 27L71 27L71 28L70 28L70 29L71 29L71 30L72 30L72 31L69 31L69 30L68 30L68 29L67 29L67 28L68 28L68 27L70 27L70 26L69 26L69 25L70 25L70 23L69 23L69 24L68 24L68 23L67 23L67 22L65 22L65 21L66 21L66 19L67 19L67 20L68 20L68 19L70 19L70 21L69 21L69 22L71 22L71 19L70 19L70 18L68 18L68 19L67 19L67 18L66 18L66 19L65 19L65 21L64 21L64 22L63 22L63 23L64 23L64 22L65 22L65 23L66 23L66 24L64 24L64 25L66 25L66 24L68 24L68 27L67 27L67 26L65 26L65 27L67 27L67 28L62 28L62 25L61 25L61 26L60 26L60 27L58 27L58 28L57 28L57 29L55 29L55 28L56 28L56 27L57 27L57 26L58 26L58 24L57 24L57 23L56 23L56 21L57 21L57 20L56 20L56 19L57 19L57 18L56 18L56 17L57 17L57 14L56 14L56 13L55 13L55 12L56 12L56 11L57 11L57 10L56 10L56 9L55 9L55 8L54 8L54 10L52 10L52 9L53 9L53 8L51 8L51 9L50 9L50 10L49 10L49 9L48 9L48 11L46 11L46 12L44 12L44 11L43 11L43 10L42 10L42 9L41 9L41 8L38 8L38 10L39 10L39 11L40 11L40 10L39 10L39 9L41 9L41 10L42 10L42 11L41 11L41 12L42 12L42 13L41 13L41 14L39 14L39 15L38 15L38 13L37 13L37 14L36 14L36 15L34 15L34 16L32 16L32 15L31 15L31 14L29 14L29 17L28 17L28 16L27 16L27 15L28 15L28 14L27 14L27 15L26 15L26 17L25 17L25 16L24 16L24 17L22 17L22 18L21 18L21 17L19 17L19 18L18 18L18 17L17 17L17 15L21 15L21 16L23 16L23 14L22 14L22 12L23 12L23 13L24 13L24 14L26 14L26 13L28 13L28 11L26 11L26 8L28 8L28 7L29 7L29 6L28 6L28 7L27 7L27 6L26 6L26 5L25 5L25 6L24 6L24 7L23 7L23 6L22 6L22 8L24 8L24 9L23 9L23 10L22 10L22 9L21 9L21 8L20 8L20 7L21 7L21 5L19 5L19 6L18 6L18 5ZM31 5L31 8L34 8L34 5ZM57 5L57 8L60 8L60 5ZM75 5L75 6L76 6L76 7L77 7L77 6L76 6L76 5ZM83 5L83 8L86 8L86 5ZM17 6L17 7L18 7L18 6ZM19 6L19 7L20 7L20 6ZM25 6L25 8L26 8L26 6ZM32 6L32 7L33 7L33 6ZM40 6L40 7L41 7L41 6ZM58 6L58 7L59 7L59 6ZM63 6L63 8L64 8L64 6ZM84 6L84 7L85 7L85 6ZM107 6L107 7L108 7L108 6ZM79 7L79 8L77 8L77 10L76 10L76 11L78 11L78 12L79 12L79 11L81 11L81 12L82 12L82 11L83 11L83 10L79 10L79 9L80 9L80 7ZM89 7L89 8L88 8L88 9L90 9L90 7ZM0 8L0 9L3 9L3 10L4 10L4 11L5 11L5 10L4 10L4 9L5 9L5 8ZM18 8L18 9L20 9L20 10L19 10L19 11L18 11L18 12L17 12L17 13L18 13L18 14L19 14L19 13L18 13L18 12L20 12L20 13L21 13L21 12L22 12L22 10L21 10L21 9L20 9L20 8ZM70 8L70 9L71 9L71 8ZM91 8L91 9L92 9L92 8ZM113 8L113 9L114 9L114 10L113 10L113 12L115 12L115 13L116 13L116 12L115 12L115 11L116 11L116 10L117 10L117 9L116 9L116 8L115 8L115 9L114 9L114 8ZM24 9L24 10L23 10L23 11L24 11L24 12L25 12L25 11L24 11L24 10L25 10L25 9ZM27 9L27 10L28 10L28 9ZM31 9L31 10L30 10L30 11L31 11L31 10L32 10L32 9ZM45 9L45 10L46 10L46 9ZM97 9L97 11L98 11L98 9ZM1 10L1 11L0 11L0 15L1 15L1 16L0 16L0 18L1 18L1 16L2 16L2 13L1 13L1 12L2 12L2 10ZM50 10L50 12L49 12L49 11L48 11L48 12L46 12L46 13L48 13L48 14L45 14L45 13L44 13L44 14L45 14L45 15L44 15L44 16L45 16L45 18L44 18L44 17L39 17L39 16L42 16L42 15L39 15L39 16L38 16L38 17L37 17L37 16L36 16L36 17L35 17L35 16L34 16L34 17L32 17L32 16L31 16L31 15L30 15L30 16L31 16L31 17L32 17L32 21L31 21L31 22L32 22L32 25L31 25L31 26L32 26L32 28L31 28L31 27L29 27L29 25L30 25L30 24L29 24L29 23L30 23L30 22L29 22L29 20L31 20L31 19L30 19L30 17L29 17L29 18L28 18L28 19L27 19L27 17L26 17L26 19L24 19L24 20L20 20L20 21L21 21L21 24L22 24L22 23L23 23L23 24L24 24L24 23L23 23L23 21L24 21L24 22L26 22L26 23L25 23L25 25L26 25L26 27L25 27L25 26L23 26L23 27L22 27L22 26L21 26L21 27L22 27L22 28L20 28L20 29L18 29L18 31L17 31L17 28L16 28L16 29L15 29L15 28L14 28L14 27L13 27L13 28L14 28L14 29L13 29L13 30L16 30L16 32L15 32L15 33L14 33L14 31L12 31L12 32L13 32L13 33L12 33L12 34L16 34L16 35L17 35L17 36L18 36L18 39L17 39L17 40L16 40L16 39L15 39L15 40L14 40L14 39L13 39L13 40L14 40L14 42L15 42L15 41L17 41L17 40L19 40L19 35L18 35L18 34L22 34L22 35L23 35L23 36L22 36L22 37L25 37L25 38L23 38L23 39L22 39L22 38L21 38L21 41L22 41L22 43L23 43L23 42L24 42L24 41L25 41L25 42L26 42L26 41L25 41L25 40L24 40L24 39L28 39L28 40L29 40L29 39L28 39L28 38L29 38L29 37L30 37L30 36L29 36L29 35L30 35L30 33L28 33L28 32L29 32L29 31L30 31L30 30L34 30L34 29L35 29L35 28L34 28L34 29L32 29L32 28L33 28L33 27L36 27L36 26L37 26L37 25L38 25L38 24L42 24L42 23L39 23L39 21L40 21L40 22L41 22L41 21L40 21L40 20L42 20L42 19L41 19L41 18L44 18L44 20L43 20L43 21L42 21L42 22L46 22L46 23L43 23L43 25L42 25L42 26L43 26L43 25L44 25L44 24L46 24L46 23L47 23L47 22L48 22L48 21L49 21L49 22L50 22L50 23L49 23L49 25L48 25L48 24L47 24L47 26L45 26L45 27L44 27L44 28L42 28L42 27L39 27L39 26L38 26L38 28L36 28L36 29L37 29L37 30L38 30L38 31L37 31L37 32L39 32L39 34L38 34L38 36L39 36L39 37L37 37L37 35L36 35L36 33L35 33L35 35L31 35L31 36L35 36L35 37L36 37L36 39L37 39L37 38L38 38L38 40L36 40L36 41L39 41L39 42L38 42L38 44L37 44L37 45L38 45L38 48L36 48L36 50L35 50L35 49L34 49L34 48L32 48L32 51L33 51L33 52L32 52L32 53L30 53L30 52L31 52L31 50L28 50L28 51L27 51L27 49L26 49L26 50L24 50L24 51L27 51L27 52L25 52L25 54L26 54L26 55L27 55L27 56L28 56L28 55L30 55L30 56L35 56L35 55L36 55L36 54L37 54L37 55L38 55L38 57L39 57L39 56L41 56L41 55L39 55L39 54L42 54L42 51L40 51L40 49L41 49L41 50L43 50L43 51L46 51L46 52L45 52L45 53L46 53L46 54L43 54L43 55L42 55L42 56L43 56L43 55L44 55L44 57L42 57L42 58L43 58L43 59L42 59L42 61L43 61L43 63L44 63L44 64L45 64L45 65L44 65L44 66L43 66L43 64L42 64L42 63L41 63L41 61L40 61L40 62L39 62L39 61L38 61L38 59L39 59L39 60L41 60L41 58L40 58L40 59L39 59L39 58L38 58L38 59L37 59L37 58L36 58L36 57L37 57L37 56L36 56L36 57L35 57L35 59L37 59L37 60L35 60L35 61L34 61L34 62L35 62L35 63L34 63L34 64L33 64L33 63L32 63L32 62L33 62L33 61L32 61L32 62L31 62L31 64L33 64L33 65L31 65L31 66L30 66L30 65L28 65L28 64L29 64L29 63L27 63L27 61L26 61L26 62L25 62L25 61L23 61L23 59L22 59L22 60L20 60L20 63L21 63L21 64L22 64L22 65L23 65L23 64L22 64L22 62L24 62L24 64L25 64L25 63L27 63L27 64L26 64L26 65L27 65L27 66L25 66L25 65L24 65L24 67L23 67L23 68L22 68L22 69L21 69L21 71L20 71L20 70L19 70L19 71L20 71L20 72L21 72L21 71L23 71L23 72L24 72L24 73L25 73L25 70L24 70L24 69L25 69L25 67L27 67L27 68L26 68L26 69L27 69L27 70L26 70L26 72L28 72L28 73L29 73L29 71L30 71L30 70L29 70L29 69L27 69L27 68L29 68L29 66L30 66L30 67L31 67L31 66L35 66L35 67L36 67L36 66L38 66L38 67L37 67L37 68L38 68L38 69L36 69L36 68L33 68L33 67L32 67L32 68L33 68L33 69L32 69L32 71L31 71L31 72L33 72L33 71L35 71L35 70L38 70L38 71L39 71L39 72L43 72L43 71L44 71L44 72L45 72L45 71L46 71L46 72L47 72L47 71L48 71L48 70L46 70L46 69L47 69L47 68L46 68L46 67L47 67L47 66L48 66L48 65L50 65L50 67L49 67L49 68L48 68L48 69L49 69L49 71L51 71L51 72L50 72L50 73L49 73L49 72L48 72L48 74L50 74L50 75L49 75L49 76L47 76L47 74L45 74L45 75L44 75L44 74L43 74L43 73L40 73L40 74L43 74L43 75L44 75L44 76L43 76L43 78L44 78L44 77L45 77L45 79L42 79L42 82L44 82L44 84L43 84L43 85L44 85L44 86L43 86L43 87L42 87L42 88L41 88L41 89L43 89L43 87L44 87L44 88L45 88L45 87L46 87L46 86L45 86L45 85L46 85L46 84L45 84L45 83L47 83L47 82L49 82L49 81L50 81L50 83L49 83L49 84L50 84L50 85L55 85L55 84L56 84L56 82L58 82L58 81L59 81L59 78L58 78L58 77L60 77L60 76L61 76L61 77L63 77L63 78L62 78L62 80L60 80L60 81L61 81L61 82L62 82L62 83L61 83L61 87L58 87L58 88L57 88L57 90L56 90L56 92L58 92L58 91L57 91L57 90L59 90L59 91L60 91L60 90L59 90L59 88L60 88L60 89L61 89L61 90L62 90L62 89L61 89L61 87L63 87L63 88L69 88L69 87L70 87L70 88L71 88L71 89L73 89L73 90L70 90L70 89L68 89L68 90L69 90L69 91L65 91L65 90L67 90L67 89L65 89L65 90L63 90L63 91L61 91L61 94L62 94L62 95L63 95L63 96L64 96L64 97L63 97L63 98L65 98L65 97L67 97L67 95L68 95L68 94L69 94L69 95L71 95L71 92L70 92L70 91L73 91L73 90L74 90L74 91L75 91L75 92L76 92L76 94L77 94L77 95L78 95L78 96L79 96L79 95L80 95L80 96L81 96L81 97L83 97L83 98L87 98L87 99L88 99L88 98L87 98L87 97L90 97L90 98L89 98L89 99L90 99L90 100L91 100L91 99L90 99L90 98L91 98L91 97L90 97L90 96L91 96L91 95L90 95L90 93L91 93L91 91L90 91L90 92L89 92L89 91L88 91L88 90L87 90L87 89L89 89L89 86L91 86L91 87L90 87L90 88L91 88L91 87L92 87L92 88L94 88L94 87L96 87L96 86L97 86L97 85L95 85L95 86L92 86L92 85L93 85L93 83L92 83L92 85L91 85L91 84L89 84L89 82L91 82L91 81L90 81L90 80L91 80L91 79L90 79L90 78L89 78L89 77L88 77L88 79L87 79L87 78L84 78L84 77L85 77L85 75L84 75L84 74L83 74L83 73L84 73L84 72L82 72L82 71L83 71L83 68L81 68L81 67L85 67L85 68L84 68L84 69L85 69L85 68L86 68L86 67L87 67L87 68L88 68L88 69L89 69L89 68L90 68L90 69L92 69L92 68L94 68L94 69L95 69L95 68L94 68L94 67L96 67L96 66L98 66L98 67L99 67L99 66L98 66L98 65L96 65L96 64L95 64L95 66L92 66L92 65L93 65L93 63L92 63L92 64L91 64L91 63L90 63L90 61L91 61L91 62L92 62L92 61L93 61L93 60L94 60L94 59L95 59L95 57L94 57L94 58L93 58L93 57L92 57L92 58L91 58L91 55L88 55L88 54L89 54L89 53L92 53L92 52L94 52L94 53L93 53L93 54L94 54L94 55L92 55L92 56L94 56L94 55L96 55L96 54L94 54L94 53L97 53L97 55L100 55L100 57L101 57L101 56L102 56L102 58L104 58L104 57L103 57L103 56L102 56L102 55L100 55L100 54L98 54L98 53L97 53L97 52L98 52L98 51L97 51L97 50L98 50L98 47L99 47L99 46L98 46L98 47L97 47L97 48L93 48L93 46L92 46L92 45L93 45L93 44L94 44L94 45L95 45L95 44L96 44L96 43L97 43L97 42L98 42L98 41L99 41L99 40L100 40L100 41L101 41L101 40L103 40L103 41L102 41L102 42L101 42L101 43L102 43L102 42L103 42L103 41L104 41L104 40L103 40L103 39L99 39L99 38L98 38L98 39L97 39L97 38L96 38L96 39L97 39L97 40L96 40L96 41L95 41L95 42L92 42L92 41L91 41L91 39L92 39L92 40L93 40L93 39L92 39L92 38L93 38L93 35L92 35L92 34L91 34L91 36L92 36L92 38L91 38L91 37L89 37L89 36L90 36L90 35L86 35L86 36L88 36L88 37L89 37L89 38L87 38L87 37L84 37L84 38L83 38L83 37L82 37L82 39L81 39L81 36L82 36L82 35L80 35L80 33L79 33L79 35L78 35L78 36L77 36L77 35L75 35L75 34L74 34L74 35L75 35L75 36L73 36L73 37L72 37L72 36L69 36L69 35L71 35L71 34L73 34L73 32L74 32L74 31L75 31L75 32L76 32L76 34L77 34L77 33L78 33L78 32L79 32L79 31L80 31L80 32L81 32L81 31L82 31L82 30L81 30L81 29L83 29L83 30L87 30L87 32L89 32L89 33L87 33L87 34L89 34L89 33L91 33L91 31L89 31L89 30L92 30L92 29L91 29L91 28L90 28L90 27L89 27L89 25L91 25L91 27L92 27L92 28L93 28L93 27L92 27L92 25L93 25L93 24L94 24L94 23L95 23L95 22L94 22L94 23L93 23L93 21L92 21L92 20L91 20L91 19L90 19L90 17L88 17L88 16L90 16L90 15L87 15L87 16L86 16L86 18L85 18L85 19L87 19L87 20L86 20L86 23L85 23L85 24L84 24L84 25L83 25L83 24L82 24L82 23L84 23L84 22L81 22L81 21L83 21L83 20L82 20L82 19L83 19L83 17L85 17L85 16L83 16L83 15L80 15L80 16L78 16L78 17L79 17L79 18L81 18L81 19L80 19L80 20L81 20L81 21L80 21L80 24L77 24L77 25L76 25L76 26L77 26L77 25L79 25L79 29L78 29L78 30L79 30L79 31L76 31L76 30L73 30L73 32L71 32L71 34L70 34L70 32L69 32L69 31L68 31L68 33L67 33L67 31L66 31L66 33L64 33L64 34L61 34L61 35L60 35L60 36L61 36L61 37L62 37L62 38L60 38L60 39L63 39L63 40L61 40L61 41L62 41L62 42L61 42L61 43L60 43L60 44L61 44L61 43L62 43L62 44L64 44L64 46L62 46L62 45L61 45L61 46L62 46L62 48L61 48L61 50L62 50L62 51L64 51L64 52L62 52L62 54L61 54L61 55L60 55L60 56L61 56L61 57L62 57L62 59L61 59L61 61L62 61L62 64L61 64L61 63L60 63L60 64L59 64L59 62L60 62L60 61L59 61L59 62L55 62L55 61L56 61L56 59L55 59L55 60L54 60L54 59L51 59L51 58L54 58L54 57L53 57L53 56L55 56L55 57L56 57L56 56L55 56L55 55L57 55L57 56L59 56L59 55L57 55L57 54L59 54L59 53L58 53L58 52L59 52L59 51L58 51L58 52L57 52L57 53L56 53L56 52L55 52L55 51L56 51L56 50L59 50L59 49L57 49L57 48L60 48L60 46L59 46L59 45L58 45L58 44L59 44L59 43L57 43L57 45L56 45L56 43L54 43L54 44L53 44L53 43L51 43L51 42L50 42L50 43L49 43L49 41L52 41L52 42L54 42L54 41L56 41L56 42L60 42L60 41L59 41L59 38L58 38L58 39L57 39L57 37L59 37L59 35L58 35L58 36L56 36L56 35L52 35L52 34L51 34L51 32L52 32L52 33L54 33L54 34L55 34L55 33L54 33L54 32L55 32L55 31L56 31L56 30L55 30L55 29L54 29L54 28L55 28L55 27L56 27L56 26L57 26L57 24L56 24L56 23L55 23L55 24L53 24L53 23L54 23L54 22L53 22L53 21L54 21L54 20L55 20L55 19L54 19L54 18L55 18L55 17L56 17L56 14L55 14L55 13L54 13L54 14L55 14L55 15L53 15L53 14L50 14L50 12L51 12L51 13L53 13L53 11L52 11L52 10ZM55 10L55 11L54 11L54 12L55 12L55 11L56 11L56 10ZM20 11L20 12L21 12L21 11ZM33 11L33 12L34 12L34 11ZM42 11L42 12L43 12L43 11ZM51 11L51 12L52 12L52 11ZM70 11L70 12L69 12L69 13L70 13L70 12L71 12L71 11ZM72 11L72 13L71 13L71 15L69 15L69 17L71 17L71 18L72 18L72 16L73 16L73 15L74 15L74 13L76 13L76 12L73 12L73 11ZM39 12L39 13L40 13L40 12ZM6 13L6 14L7 14L7 13ZM11 13L11 15L12 15L12 13ZM15 13L15 14L14 14L14 15L16 15L16 13ZM64 13L64 14L65 14L65 13ZM66 13L66 14L67 14L67 13ZM72 13L72 14L73 14L73 13ZM4 14L4 15L5 15L5 14ZM21 14L21 15L22 15L22 14ZM49 14L49 15L46 15L46 16L49 16L49 17L48 17L48 18L49 18L49 19L47 19L47 20L49 20L49 19L50 19L50 18L52 18L52 17L55 17L55 16L51 16L51 17L50 17L50 16L49 16L49 15L50 15L50 14ZM112 14L112 15L110 15L110 17L109 17L109 19L110 19L110 20L109 20L109 21L110 21L110 20L111 20L111 19L112 19L112 21L113 21L113 18L114 18L114 17L113 17L113 15L114 15L114 14ZM101 15L101 16L102 16L102 15ZM115 15L115 16L116 16L116 15ZM12 16L12 17L13 17L13 16ZM64 16L64 17L61 17L61 19L62 19L62 20L61 20L61 21L63 21L63 19L64 19L64 17L65 17L65 16ZM111 16L111 17L112 17L112 18L113 18L113 17L112 17L112 16ZM24 17L24 18L25 18L25 17ZM36 17L36 19L35 19L35 21L34 21L34 22L33 22L33 21L32 21L32 22L33 22L33 23L34 23L34 24L33 24L33 26L34 26L34 25L35 25L35 24L36 24L36 25L37 25L37 23L38 23L38 21L39 21L39 20L38 20L38 18L37 18L37 17ZM46 17L46 18L47 18L47 17ZM73 17L73 19L72 19L72 21L74 21L74 20L75 20L75 21L76 21L76 20L75 20L75 19L76 19L76 18L75 18L75 19L74 19L74 17ZM87 17L87 18L88 18L88 19L89 19L89 20L88 20L88 21L87 21L87 23L86 23L86 25L84 25L84 27L83 27L83 26L82 26L82 25L81 25L81 24L80 24L80 25L81 25L81 26L80 26L80 27L81 27L81 26L82 26L82 28L83 28L83 29L85 29L85 28L86 28L86 29L87 29L87 27L88 27L88 26L87 26L87 27L86 27L86 25L88 25L88 24L87 24L87 23L88 23L88 21L90 21L90 22L89 22L89 23L91 23L91 25L92 25L92 21L91 21L91 20L90 20L90 19L89 19L89 18L88 18L88 17ZM20 18L20 19L21 19L21 18ZM22 18L22 19L23 19L23 18ZM33 18L33 19L34 19L34 18ZM39 18L39 19L40 19L40 18ZM62 18L62 19L63 19L63 18ZM110 18L110 19L111 19L111 18ZM18 19L18 20L19 20L19 19ZM45 19L45 20L44 20L44 21L46 21L46 22L47 22L47 21L46 21L46 19ZM51 19L51 20L50 20L50 21L51 21L51 22L52 22L52 21L51 21L51 20L52 20L52 19ZM53 19L53 20L54 20L54 19ZM73 19L73 20L74 20L74 19ZM78 19L78 21L79 21L79 19ZM6 20L6 21L7 21L7 20ZM18 21L18 22L19 22L19 21ZM26 21L26 22L28 22L28 21ZM36 21L36 22L34 22L34 23L37 23L37 21ZM96 21L96 23L97 23L97 21ZM116 21L116 22L117 22L117 21ZM0 22L0 23L1 23L1 22ZM72 22L72 23L71 23L71 24L72 24L72 23L74 23L74 24L75 24L75 23L76 23L76 22L75 22L75 23L74 23L74 22ZM6 23L6 24L7 24L7 23ZM101 23L101 24L102 24L102 23ZM52 24L52 25L53 25L53 27L55 27L55 26L56 26L56 25L55 25L55 26L54 26L54 25L53 25L53 24ZM106 24L106 25L107 25L107 24ZM27 25L27 27L26 27L26 30L25 30L25 27L23 27L23 28L24 28L24 30L25 30L25 31L26 31L26 32L27 32L27 31L26 31L26 30L30 30L30 29L31 29L31 28L30 28L30 29L27 29L27 27L28 27L28 25ZM6 26L6 27L7 27L7 26ZM48 26L48 27L46 27L46 28L44 28L44 29L47 29L47 28L49 28L49 29L50 29L50 30L47 30L47 31L45 31L45 32L44 32L44 31L43 31L43 29L41 29L41 28L40 28L40 29L41 29L41 33L40 33L40 34L41 34L41 35L42 35L42 36L41 36L41 37L42 37L42 38L39 38L39 39L43 39L43 40L41 40L41 45L42 45L42 44L43 44L43 47L42 47L42 46L41 46L41 47L42 47L42 49L43 49L43 47L45 47L45 46L46 46L46 45L44 45L44 44L43 44L43 43L45 43L45 44L47 44L47 45L48 45L48 46L47 46L47 47L46 47L46 48L44 48L44 49L45 49L45 50L47 50L47 53L48 53L48 54L47 54L47 55L48 55L48 56L49 56L49 57L47 57L47 56L45 56L45 57L44 57L44 58L46 58L46 57L47 57L47 59L46 59L46 60L45 60L45 61L44 61L44 63L45 63L45 62L46 62L46 61L48 61L48 62L47 62L47 63L46 63L46 65L45 65L45 66L46 66L46 65L47 65L47 63L48 63L48 64L51 64L51 65L52 65L52 63L54 63L54 64L53 64L53 65L55 65L55 66L53 66L53 67L52 67L52 66L51 66L51 67L50 67L50 69L52 69L52 71L54 71L54 72L53 72L53 73L51 73L51 75L50 75L50 76L51 76L51 75L52 75L52 74L53 74L53 77L56 77L56 76L57 76L57 77L58 77L58 76L57 76L57 75L56 75L56 74L55 74L55 73L57 73L57 74L58 74L58 75L59 75L59 74L60 74L60 75L62 75L62 76L63 76L63 75L65 75L65 76L64 76L64 79L63 79L63 83L65 83L65 84L64 84L64 85L63 85L63 84L62 84L62 85L63 85L63 87L69 87L69 86L68 86L68 85L69 85L69 84L68 84L68 82L69 82L69 83L70 83L70 86L71 86L71 87L72 87L72 88L74 88L74 90L75 90L75 89L77 89L77 88L80 88L80 89L78 89L78 90L77 90L77 91L79 91L79 92L78 92L78 93L79 93L79 94L78 94L78 95L79 95L79 94L80 94L80 95L81 95L81 96L82 96L82 95L83 95L83 97L85 97L85 95L86 95L86 97L87 97L87 94L88 94L88 95L89 95L89 96L90 96L90 95L89 95L89 94L88 94L88 93L87 93L87 94L84 94L84 91L86 91L86 88L85 88L85 87L87 87L87 86L88 86L88 85L89 85L89 84L87 84L87 81L89 81L89 80L90 80L90 79L89 79L89 80L87 80L87 79L83 79L83 78L81 78L81 77L80 77L80 75L79 75L79 74L80 74L80 73L79 73L79 74L77 74L77 73L76 73L76 75L77 75L77 76L78 76L78 77L77 77L77 78L76 78L76 79L75 79L75 80L76 80L76 81L77 81L77 80L76 80L76 79L79 79L79 80L78 80L78 82L76 82L76 83L75 83L75 84L74 84L74 80L73 80L73 79L74 79L74 77L76 77L76 76L75 76L75 75L73 75L73 74L75 74L75 73L73 73L73 71L74 71L74 70L75 70L75 69L76 69L76 68L75 68L75 67L77 67L77 68L78 68L78 69L81 69L81 68L80 68L80 67L79 67L79 64L80 64L80 63L81 63L81 64L83 64L83 66L85 66L85 67L86 67L86 64L84 64L84 63L83 63L83 62L79 62L79 60L80 60L80 57L81 57L81 61L84 61L84 62L86 62L86 63L87 63L87 61L90 61L90 59L91 59L91 61L92 61L92 59L91 59L91 58L90 58L90 57L89 57L89 56L86 56L86 55L85 55L85 53L86 53L86 54L87 54L87 53L89 53L89 52L90 52L90 51L91 51L91 52L92 52L92 50L93 50L93 49L92 49L92 50L91 50L91 49L90 49L90 47L91 47L91 48L92 48L92 46L90 46L90 47L89 47L89 46L88 46L88 47L87 47L87 49L86 49L86 50L84 50L84 52L81 52L81 53L80 53L80 54L81 54L81 55L83 55L83 56L82 56L82 57L81 57L81 56L80 56L80 55L79 55L79 52L80 52L80 51L83 51L83 50L82 50L82 49L85 49L85 48L86 48L86 47L85 47L85 48L82 48L82 49L80 49L80 51L79 51L79 50L78 50L78 49L79 49L79 48L80 48L80 47L82 47L82 46L86 46L86 43L87 43L87 44L88 44L88 43L89 43L89 44L90 44L90 45L91 45L91 44L92 44L92 43L91 43L91 41L86 41L86 39L85 39L85 38L84 38L84 40L82 40L82 41L83 41L83 42L85 42L85 43L84 43L84 44L83 44L83 43L82 43L82 42L81 42L81 40L80 40L80 39L79 39L79 37L80 37L80 35L79 35L79 37L78 37L78 39L77 39L77 40L78 40L78 41L75 41L75 39L74 39L74 38L77 38L77 37L74 37L74 38L73 38L73 39L72 39L72 38L71 38L71 37L70 37L70 38L71 38L71 40L70 40L70 39L69 39L69 40L68 40L68 38L64 38L64 37L66 37L66 36L68 36L68 37L69 37L69 36L68 36L68 34L69 34L69 33L68 33L68 34L67 34L67 35L66 35L66 34L64 34L64 35L66 35L66 36L63 36L63 35L61 35L61 36L62 36L62 37L63 37L63 39L65 39L65 41L64 41L64 40L63 40L63 43L66 43L66 45L65 45L65 47L67 47L67 48L62 48L62 49L67 49L67 50L66 50L66 51L65 51L65 50L64 50L64 51L65 51L65 52L66 52L66 51L67 51L67 53L65 53L65 55L66 55L66 56L65 56L65 57L67 57L67 58L64 58L64 57L63 57L63 59L62 59L62 61L63 61L63 62L64 62L64 63L67 63L67 64L65 64L65 65L64 65L64 66L63 66L63 64L62 64L62 66L61 66L61 64L60 64L60 65L55 65L55 64L56 64L56 63L55 63L55 62L52 62L52 63L50 63L50 62L51 62L51 61L54 61L54 60L51 60L51 61L50 61L50 60L49 60L49 59L50 59L50 58L51 58L51 57L52 57L52 56L51 56L51 55L52 55L52 54L53 54L53 55L55 55L55 54L54 54L54 53L55 53L55 52L53 52L53 53L52 53L52 54L51 54L51 53L50 53L50 50L49 50L49 47L50 47L50 48L51 48L51 49L52 49L52 48L51 48L51 47L50 47L50 46L49 46L49 45L50 45L50 44L49 44L49 43L48 43L48 42L47 42L47 41L48 41L48 40L52 40L52 41L53 41L53 40L52 40L52 39L55 39L55 40L57 40L57 41L58 41L58 40L57 40L57 39L56 39L56 38L54 38L54 37L55 37L55 36L53 36L53 37L52 37L52 36L51 36L51 37L52 37L52 39L50 39L50 37L49 37L49 36L50 36L50 34L49 34L49 33L50 33L50 32L49 32L49 31L52 31L52 30L51 30L51 27L50 27L50 26ZM63 26L63 27L64 27L64 26ZM4 27L4 28L5 28L5 27ZM60 27L60 28L58 28L58 29L57 29L57 30L59 30L59 29L60 29L60 30L62 30L62 31L61 31L61 33L63 33L63 31L64 31L64 32L65 32L65 31L64 31L64 29L62 29L62 28L61 28L61 27ZM76 27L76 28L78 28L78 27ZM71 28L71 29L73 29L73 28ZM99 28L99 29L100 29L100 28ZM104 28L104 29L105 29L105 28ZM111 28L111 29L110 29L110 30L111 30L111 29L112 29L112 30L113 30L113 28ZM21 29L21 30L22 30L22 31L21 31L21 32L19 32L19 31L18 31L18 33L16 33L16 34L18 34L18 33L21 33L21 32L24 32L24 34L23 34L23 33L22 33L22 34L23 34L23 35L24 35L24 36L25 36L25 35L26 35L26 34L27 34L27 36L26 36L26 37L27 37L27 36L28 36L28 37L29 37L29 36L28 36L28 35L29 35L29 34L27 34L27 33L25 33L25 32L24 32L24 31L23 31L23 29ZM38 29L38 30L39 30L39 31L40 31L40 30L39 30L39 29ZM79 29L79 30L80 30L80 29ZM88 29L88 30L89 30L89 29ZM114 29L114 30L115 30L115 29ZM35 30L35 31L36 31L36 30ZM54 30L54 31L53 31L53 32L54 32L54 31L55 31L55 30ZM96 30L96 31L97 31L97 30ZM5 31L5 34L8 34L8 31ZM31 31L31 34L34 34L34 31ZM42 31L42 32L43 32L43 33L42 33L42 34L43 34L43 33L44 33L44 36L43 36L43 38L44 38L44 40L46 40L46 41L44 41L44 42L45 42L45 43L47 43L47 42L46 42L46 41L47 41L47 39L49 39L49 38L47 38L47 37L48 37L48 36L47 36L47 34L48 34L48 32L47 32L47 34L46 34L46 33L44 33L44 32L43 32L43 31ZM57 31L57 34L60 34L60 31ZM83 31L83 34L86 34L86 31ZM106 31L106 32L105 32L105 33L106 33L106 32L107 32L107 31ZM109 31L109 34L112 34L112 31ZM6 32L6 33L7 33L7 32ZM32 32L32 33L33 33L33 32ZM58 32L58 33L59 33L59 32ZM84 32L84 33L85 33L85 32ZM93 32L93 33L94 33L94 34L95 34L95 33L94 33L94 32ZM110 32L110 33L111 33L111 32ZM24 34L24 35L25 35L25 34ZM10 35L10 36L11 36L11 35ZM20 35L20 37L21 37L21 35ZM35 35L35 36L36 36L36 35ZM45 35L45 37L44 37L44 38L45 38L45 39L46 39L46 38L45 38L45 37L46 37L46 35ZM83 35L83 36L85 36L85 35ZM105 35L105 36L107 36L107 35ZM3 36L3 37L2 37L2 38L3 38L3 37L5 37L5 38L6 38L6 39L7 39L7 38L6 38L6 37L5 37L5 36ZM31 37L31 38L32 38L32 37ZM89 38L89 39L91 39L91 38ZM105 38L105 39L106 39L106 40L107 40L107 41L106 41L106 42L105 42L105 43L106 43L106 44L107 44L107 43L108 43L108 46L111 46L111 47L112 47L112 46L111 46L111 45L112 45L112 44L111 44L111 45L110 45L110 44L109 44L109 43L108 43L108 41L110 41L110 40L109 40L109 39L108 39L108 40L107 40L107 39L106 39L106 38ZM111 38L111 39L112 39L112 38ZM66 39L66 41L65 41L65 42L66 42L66 41L67 41L67 39ZM73 39L73 41L71 41L71 42L70 42L70 40L69 40L69 41L68 41L68 42L67 42L67 44L68 44L68 45L66 45L66 46L67 46L67 47L68 47L68 48L69 48L69 49L68 49L68 50L70 50L70 51L68 51L68 52L70 52L70 53L71 53L71 54L69 54L69 53L67 53L67 55L68 55L68 56L67 56L67 57L68 57L68 62L67 62L67 59L63 59L63 61L64 61L64 62L67 62L67 63L70 63L70 65L69 65L69 67L68 67L68 69L67 69L67 67L66 67L66 69L67 69L67 71L66 71L66 70L65 70L65 71L64 71L64 70L63 70L63 69L65 69L65 67L63 67L63 66L62 66L62 71L61 71L61 70L60 70L60 71L61 71L61 74L62 74L62 75L63 75L63 74L62 74L62 72L63 72L63 71L64 71L64 72L65 72L65 73L67 73L67 75L66 75L66 74L65 74L65 75L66 75L66 76L65 76L65 78L66 78L66 77L70 77L70 79L68 79L68 81L67 81L67 82L68 82L68 81L69 81L69 80L70 80L70 79L73 79L73 78L71 78L71 75L72 75L72 74L71 74L71 75L70 75L70 74L68 74L68 73L67 73L67 71L68 71L68 72L69 72L69 73L70 73L70 72L71 72L71 73L72 73L72 70L71 70L71 65L73 65L73 66L72 66L72 67L73 67L73 70L74 70L74 69L75 69L75 68L74 68L74 67L75 67L75 66L76 66L76 65L75 65L75 66L74 66L74 64L71 64L71 62L74 62L74 63L75 63L75 64L77 64L77 66L78 66L78 63L77 63L77 61L76 61L76 60L79 60L79 59L76 59L76 58L78 58L78 57L80 57L80 56L79 56L79 55L78 55L78 54L77 54L77 53L75 53L75 52L79 52L79 51L77 51L77 49L76 49L76 51L75 51L75 50L70 50L70 48L71 48L71 47L72 47L72 46L74 46L74 47L73 47L73 48L72 48L72 49L73 49L73 48L77 48L77 46L75 46L75 45L78 45L78 44L79 44L79 43L78 43L78 44L77 44L77 43L76 43L76 42L75 42L75 41L74 41L74 39ZM78 39L78 40L79 40L79 39ZM98 39L98 40L99 40L99 39ZM22 40L22 41L23 41L23 40ZM31 40L31 41L32 41L32 40ZM111 40L111 41L112 41L112 40ZM42 41L42 42L43 42L43 41ZM73 41L73 43L69 43L69 42L68 42L68 43L69 43L69 44L72 44L72 45L70 45L70 46L72 46L72 45L73 45L73 43L74 43L74 45L75 45L75 43L74 43L74 41ZM78 41L78 42L79 42L79 41ZM96 41L96 42L95 42L95 43L94 43L94 44L95 44L95 43L96 43L96 42L97 42L97 41ZM31 42L31 43L32 43L32 42ZM33 42L33 43L34 43L34 42ZM80 42L80 46L82 46L82 45L83 45L83 44L82 44L82 43L81 43L81 42ZM87 42L87 43L88 43L88 42ZM106 42L106 43L107 43L107 42ZM110 42L110 43L111 43L111 42ZM39 43L39 44L38 44L38 45L39 45L39 47L40 47L40 45L39 45L39 44L40 44L40 43ZM90 43L90 44L91 44L91 43ZM51 44L51 45L52 45L52 46L53 46L53 48L54 48L54 49L53 49L53 51L55 51L55 50L56 50L56 49L55 49L55 48L54 48L54 47L55 47L55 46L56 46L56 45L55 45L55 44L54 44L54 46L53 46L53 44ZM81 44L81 45L82 45L82 44ZM3 45L3 48L2 48L2 50L4 50L4 51L5 51L5 50L4 50L4 49L3 49L3 48L4 48L4 47L5 47L5 46L4 46L4 45ZM14 45L14 46L16 46L16 47L17 47L17 46L16 46L16 45ZM22 45L22 46L23 46L23 47L22 47L22 48L23 48L23 47L24 47L24 46L23 46L23 45ZM57 45L57 47L56 47L56 48L57 48L57 47L59 47L59 46L58 46L58 45ZM96 45L96 46L94 46L94 47L96 47L96 46L97 46L97 45ZM18 46L18 47L19 47L19 46ZM20 46L20 47L21 47L21 46ZM25 46L25 47L26 47L26 46ZM48 46L48 47L49 47L49 46ZM78 47L78 48L79 48L79 47ZM88 47L88 48L89 48L89 47ZM104 47L104 48L105 48L105 49L107 49L107 48L105 48L105 47ZM38 48L38 50L36 50L36 51L35 51L35 50L34 50L34 51L35 51L35 52L37 52L37 51L38 51L38 50L39 50L39 49L40 49L40 48ZM47 48L47 49L48 49L48 48ZM6 49L6 50L7 50L7 49ZM88 49L88 50L86 50L86 51L85 51L85 52L89 52L89 51L88 51L88 50L89 50L89 49ZM95 49L95 51L94 51L94 52L97 52L97 51L96 51L96 50L97 50L97 49ZM113 49L113 50L114 50L114 51L115 51L115 50L116 50L116 49L115 49L115 50L114 50L114 49ZM18 50L18 51L17 51L17 52L19 52L19 50ZM48 50L48 51L49 51L49 50ZM110 50L110 51L111 51L111 52L110 52L110 53L111 53L111 52L112 52L112 53L113 53L113 51L112 51L112 50ZM39 51L39 52L40 52L40 51ZM60 51L60 52L61 52L61 51ZM70 51L70 52L73 52L73 53L72 53L72 54L75 54L75 53L74 53L74 52L75 52L75 51ZM43 52L43 53L44 53L44 52ZM48 52L48 53L49 53L49 52ZM114 52L114 53L115 53L115 52ZM26 53L26 54L27 54L27 55L28 55L28 54L27 54L27 53ZM29 53L29 54L30 54L30 53ZM33 53L33 55L35 55L35 54L36 54L36 53L35 53L35 54L34 54L34 53ZM37 53L37 54L38 54L38 53ZM82 53L82 54L83 54L83 55L84 55L84 56L85 56L85 55L84 55L84 54L83 54L83 53ZM17 54L17 56L16 56L16 58L17 58L17 56L18 56L18 54ZM31 54L31 55L32 55L32 54ZM48 54L48 55L49 55L49 56L50 56L50 55L51 55L51 54L50 54L50 55L49 55L49 54ZM68 54L68 55L69 55L69 54ZM76 54L76 55L71 55L71 57L70 57L70 56L69 56L69 57L70 57L70 58L69 58L69 59L70 59L70 61L69 61L69 62L71 62L71 61L74 61L74 62L76 62L76 61L75 61L75 59L74 59L74 56L75 56L75 57L76 57L76 55L77 55L77 57L78 57L78 55L77 55L77 54ZM0 55L0 56L1 56L1 55ZM6 55L6 56L7 56L7 55ZM61 55L61 56L64 56L64 55ZM25 56L25 57L24 57L24 58L25 58L25 59L27 59L27 60L30 60L30 59L27 59L27 58L28 58L28 57L26 57L26 56ZM116 56L116 57L115 57L115 58L117 58L117 56ZM5 57L5 60L8 60L8 57ZM25 57L25 58L26 58L26 57ZM29 57L29 58L30 58L30 57ZM31 57L31 60L34 60L34 57ZM57 57L57 60L60 60L60 57ZM83 57L83 60L86 60L86 57ZM88 57L88 58L87 58L87 59L88 59L88 60L89 60L89 59L88 59L88 58L89 58L89 57ZM106 57L106 58L107 58L107 57ZM109 57L109 60L112 60L112 57ZM6 58L6 59L7 59L7 58ZM32 58L32 59L33 59L33 58ZM58 58L58 59L59 59L59 58ZM70 58L70 59L73 59L73 60L74 60L74 59L73 59L73 58ZM84 58L84 59L85 59L85 58ZM110 58L110 59L111 59L111 58ZM3 59L3 61L4 61L4 59ZM12 59L12 61L13 61L13 59ZM43 59L43 60L44 60L44 59ZM47 59L47 60L48 60L48 59ZM64 60L64 61L65 61L65 60ZM116 60L116 61L117 61L117 60ZM21 61L21 62L22 62L22 61ZM6 62L6 63L5 63L5 64L6 64L6 63L7 63L7 62ZM37 62L37 63L35 63L35 64L36 64L36 65L37 65L37 64L38 64L38 66L39 66L39 67L38 67L38 68L39 68L39 67L40 67L40 65L41 65L41 67L43 67L43 68L40 68L40 69L39 69L39 70L40 70L40 69L42 69L42 70L41 70L41 71L43 71L43 70L44 70L44 71L45 71L45 68L44 68L44 67L43 67L43 66L42 66L42 64L38 64L38 62ZM48 62L48 63L49 63L49 62ZM88 62L88 63L89 63L89 64L88 64L88 65L87 65L87 66L88 66L88 67L91 67L91 68L92 68L92 66L90 66L90 65L91 65L91 64L90 64L90 63L89 63L89 62ZM12 63L12 64L13 64L13 63ZM97 63L97 64L98 64L98 63ZM67 64L67 65L66 65L66 66L67 66L67 65L68 65L68 64ZM101 64L101 67L102 67L102 68L101 68L101 69L103 69L103 68L104 68L104 70L105 70L105 68L107 68L107 69L106 69L106 70L107 70L107 71L108 71L108 70L109 70L109 69L108 69L108 67L109 67L109 68L110 68L110 67L109 67L109 66L110 66L110 65L107 65L107 66L108 66L108 67L105 67L105 66L104 66L104 67L103 67L103 66L102 66L102 65L103 65L103 64ZM114 64L114 65L115 65L115 64ZM18 65L18 66L17 66L17 67L19 67L19 65ZM80 65L80 66L81 66L81 65ZM88 65L88 66L89 66L89 65ZM3 66L3 68L4 68L4 66ZM10 66L10 67L9 67L9 69L10 69L10 70L11 70L11 66ZM56 66L56 67L54 67L54 69L53 69L53 70L54 70L54 71L55 71L55 72L57 72L57 73L60 73L60 72L59 72L59 71L57 71L57 70L56 70L56 67L57 67L57 69L58 69L58 70L59 70L59 69L60 69L60 68L59 68L59 67L61 67L61 66L59 66L59 67L57 67L57 66ZM73 66L73 67L74 67L74 66ZM113 66L113 67L114 67L114 66ZM51 67L51 68L52 68L52 67ZM78 67L78 68L79 68L79 67ZM104 67L104 68L105 68L105 67ZM23 68L23 69L24 69L24 68ZM43 68L43 69L44 69L44 68ZM6 69L6 70L7 70L7 69ZM69 69L69 70L70 70L70 69ZM107 69L107 70L108 70L108 69ZM28 70L28 71L29 71L29 70ZM55 70L55 71L56 71L56 70ZM77 70L77 71L78 71L78 72L80 72L80 71L81 71L81 70ZM17 71L17 72L18 72L18 71ZM65 71L65 72L66 72L66 71ZM100 71L100 72L99 72L99 73L100 73L100 72L101 72L101 71ZM102 71L102 72L103 72L103 71ZM81 73L81 74L82 74L82 77L84 77L84 76L83 76L83 74L82 74L82 73ZM94 73L94 74L95 74L95 73ZM102 73L102 74L104 74L104 73ZM24 74L24 76L25 76L25 74ZM22 75L22 76L23 76L23 75ZM31 75L31 77L33 77L33 76L32 76L32 75ZM54 75L54 76L55 76L55 75ZM67 75L67 76L68 76L68 75ZM69 75L69 76L70 76L70 75ZM109 75L109 76L111 76L111 77L110 77L110 79L109 79L109 78L108 78L108 79L109 79L109 80L108 80L108 81L109 81L109 80L110 80L110 82L111 82L111 81L112 81L112 82L114 82L114 80L112 80L112 79L114 79L114 78L115 78L115 77L113 77L113 75ZM36 76L36 78L37 78L37 77L38 77L38 76ZM45 76L45 77L46 77L46 78L47 78L47 79L46 79L46 81L49 81L49 80L50 80L50 79L49 79L49 78L50 78L50 77L49 77L49 78L48 78L48 77L47 77L47 76ZM72 76L72 77L73 77L73 76ZM101 76L101 78L103 78L103 77L102 77L102 76ZM6 77L6 78L8 78L8 79L11 79L11 77L9 77L9 78L8 78L8 77ZM20 77L20 80L24 80L24 79L23 79L23 77ZM79 77L79 79L80 79L80 80L79 80L79 81L80 81L80 82L79 82L79 83L80 83L80 84L79 84L79 85L78 85L78 86L79 86L79 85L80 85L80 88L81 88L81 89L80 89L80 90L81 90L81 89L83 89L83 90L85 90L85 88L84 88L84 87L82 87L82 86L81 86L81 85L82 85L82 82L83 82L83 80L82 80L82 79L80 79L80 77ZM111 77L111 79L112 79L112 77ZM18 78L18 79L19 79L19 78ZM21 78L21 79L22 79L22 78ZM26 78L26 79L25 79L25 80L26 80L26 79L27 79L27 78ZM39 78L39 79L40 79L40 78ZM52 78L52 79L51 79L51 80L52 80L52 82L51 82L51 83L50 83L50 84L51 84L51 83L55 83L55 82L56 82L56 78ZM57 78L57 80L58 80L58 78ZM60 78L60 79L61 79L61 78ZM52 79L52 80L53 80L53 79ZM64 79L64 81L66 81L66 79ZM6 80L6 81L7 81L7 80ZM17 80L17 81L16 81L16 82L17 82L17 81L18 81L18 82L19 82L19 80ZM44 80L44 82L45 82L45 80ZM54 80L54 82L55 82L55 80ZM72 80L72 81L71 81L71 82L70 82L70 83L71 83L71 82L72 82L72 83L73 83L73 82L72 82L72 81L73 81L73 80ZM80 80L80 81L81 81L81 80ZM86 80L86 81L85 81L85 82L86 82L86 81L87 81L87 80ZM23 81L23 82L24 82L24 83L23 83L23 84L24 84L24 83L25 83L25 82L24 82L24 81ZM65 82L65 83L66 83L66 82ZM5 83L5 86L8 86L8 83ZM11 83L11 84L12 84L12 83ZM20 83L20 84L19 84L19 85L17 85L17 84L16 84L16 85L17 85L17 86L16 86L16 87L15 87L15 88L16 88L16 89L17 89L17 90L14 90L14 91L17 91L17 90L19 90L19 89L20 89L20 85L21 85L21 83ZM31 83L31 86L34 86L34 83ZM35 83L35 84L36 84L36 83ZM57 83L57 86L60 86L60 83ZM76 83L76 84L75 84L75 85L74 85L74 88L77 88L77 85L76 85L76 84L77 84L77 83ZM83 83L83 86L86 86L86 83ZM104 83L104 84L105 84L105 83ZM109 83L109 86L112 86L112 83ZM6 84L6 85L7 85L7 84ZM32 84L32 85L33 85L33 84ZM58 84L58 85L59 85L59 84ZM71 84L71 86L72 86L72 87L73 87L73 84ZM84 84L84 85L85 85L85 84ZM101 84L101 85L100 85L100 86L98 86L98 87L97 87L97 88L95 88L95 89L94 89L94 91L95 91L95 92L96 92L96 91L97 91L97 93L99 93L99 94L98 94L98 95L99 95L99 94L100 94L100 93L101 93L101 92L103 92L103 94L104 94L104 93L106 93L106 91L100 91L100 90L102 90L102 89L99 89L99 88L103 88L103 90L104 90L104 88L103 88L103 87L100 87L100 86L101 86L101 85L102 85L102 84ZM110 84L110 85L111 85L111 84ZM12 85L12 86L13 86L13 85ZM64 85L64 86L66 86L66 85ZM75 85L75 87L76 87L76 85ZM103 85L103 86L104 86L104 85ZM18 86L18 88L17 88L17 89L18 89L18 88L19 88L19 86ZM5 87L5 88L7 88L7 87ZM21 87L21 88L22 88L22 87ZM81 87L81 88L82 88L82 87ZM107 87L107 88L108 88L108 89L109 89L109 88L108 88L108 87ZM97 88L97 89L95 89L95 90L97 90L97 91L98 91L98 90L99 90L99 89L98 89L98 88ZM115 88L115 90L116 90L116 88ZM97 89L97 90L98 90L98 89ZM6 90L6 91L5 91L5 92L6 92L6 93L8 93L8 92L6 92L6 91L7 91L7 90ZM30 90L30 92L29 92L29 93L30 93L30 94L32 94L32 93L30 93L30 92L31 92L31 91L32 91L32 90ZM109 90L109 91L110 91L110 92L111 92L111 91L110 91L110 90ZM11 91L11 92L10 92L10 93L12 93L12 91ZM80 91L80 92L79 92L79 93L80 93L80 94L81 94L81 95L82 95L82 94L83 94L83 93L82 93L82 92L81 92L81 91ZM87 91L87 92L88 92L88 91ZM99 91L99 92L100 92L100 91ZM25 92L25 94L26 94L26 95L27 95L27 93L26 93L26 92ZM47 92L47 93L45 93L45 95L44 95L44 94L43 94L43 95L44 95L44 97L43 97L43 96L41 96L41 97L40 97L40 98L42 98L42 97L43 97L43 98L44 98L44 99L42 99L42 100L41 100L41 99L39 99L39 103L37 103L37 104L39 104L39 105L36 105L36 106L35 106L35 107L34 107L34 108L35 108L35 107L37 107L37 106L38 106L38 107L39 107L39 108L38 108L38 109L37 109L37 108L36 108L36 109L37 109L37 110L36 110L36 111L37 111L37 110L38 110L38 109L39 109L39 108L41 108L41 106L44 106L44 108L43 108L43 111L44 111L44 110L45 110L45 109L44 109L44 108L47 108L47 107L48 107L48 106L49 106L49 105L48 105L48 104L47 104L47 103L48 103L48 102L50 102L50 104L51 104L51 106L50 106L50 107L52 107L52 108L53 108L53 107L52 107L52 105L54 105L54 104L55 104L55 103L54 103L54 104L53 104L53 102L50 102L50 101L48 101L48 100L46 100L46 99L47 99L47 98L48 98L48 99L49 99L49 100L51 100L51 96L48 96L48 97L47 97L47 96L46 96L46 97L45 97L45 95L47 95L47 93L48 93L48 92ZM59 92L59 94L58 94L58 93L57 93L57 94L58 94L58 95L59 95L59 94L60 94L60 92ZM62 92L62 94L63 94L63 95L64 95L64 94L63 94L63 93L65 93L65 95L67 95L67 94L68 94L68 93L69 93L69 94L70 94L70 93L69 93L69 92ZM80 92L80 93L81 93L81 92ZM85 92L85 93L86 93L86 92ZM35 93L35 94L36 94L36 93ZM66 93L66 94L67 94L67 93ZM6 94L6 95L7 95L7 94ZM17 94L17 97L19 97L19 96L18 96L18 94ZM37 94L37 95L33 95L33 97L30 97L30 96L28 96L28 97L30 97L30 99L31 99L31 98L33 98L33 100L34 100L34 98L33 98L33 97L35 97L35 96L37 96L37 95L38 95L38 94ZM41 94L41 95L42 95L42 94ZM21 95L21 96L24 96L24 95ZM31 95L31 96L32 96L32 95ZM100 96L100 97L101 97L101 98L100 98L100 99L101 99L101 100L102 100L102 99L103 99L103 100L104 100L104 102L105 102L105 100L106 100L106 99L105 99L105 100L104 100L104 98L102 98L102 96ZM103 96L103 97L105 97L105 96ZM114 96L114 97L116 97L116 96ZM5 97L5 99L4 99L4 100L5 100L5 101L3 101L3 102L2 102L2 103L3 103L3 104L5 104L5 105L6 105L6 104L7 104L7 103L6 103L6 102L8 102L8 99L9 99L9 100L10 100L10 101L9 101L9 102L10 102L10 103L11 103L11 100L10 100L10 99L11 99L11 98L10 98L10 99L9 99L9 98L7 98L7 97ZM48 97L48 98L49 98L49 99L50 99L50 98L49 98L49 97ZM6 98L6 99L7 99L7 98ZM17 98L17 99L18 99L18 98ZM19 98L19 99L20 99L20 100L21 100L21 99L22 99L22 98L21 98L21 99L20 99L20 98ZM45 98L45 99L46 99L46 98ZM101 98L101 99L102 99L102 98ZM2 99L2 100L3 100L3 99ZM12 99L12 100L13 100L13 99ZM83 99L83 100L85 100L85 99ZM6 100L6 101L5 101L5 102L6 102L6 101L7 101L7 100ZM27 100L27 101L28 101L28 102L29 102L29 103L31 103L31 104L32 104L32 102L30 102L30 101L31 101L31 100L29 100L29 101L28 101L28 100ZM40 100L40 101L41 101L41 100ZM43 100L43 101L44 101L44 100ZM45 100L45 102L44 102L44 103L43 103L43 102L41 102L41 105L44 105L44 106L45 106L45 105L47 105L47 104L46 104L46 103L45 103L45 102L48 102L48 101L46 101L46 100ZM93 100L93 101L92 101L92 102L93 102L93 101L94 101L94 100ZM116 100L116 101L117 101L117 100ZM13 101L13 102L12 102L12 103L14 103L14 101ZM37 101L37 102L38 102L38 101ZM95 101L95 102L96 102L96 103L94 103L94 104L95 104L95 105L94 105L94 106L95 106L95 105L96 105L96 104L97 104L97 103L98 103L98 102L96 102L96 101ZM106 101L106 102L107 102L107 103L108 103L108 102L107 102L107 101ZM3 102L3 103L4 103L4 102ZM33 102L33 105L34 105L34 102ZM75 102L75 103L76 103L76 102ZM100 102L100 103L101 103L101 104L102 104L102 103L101 103L101 102ZM5 103L5 104L6 104L6 103ZM8 103L8 104L9 104L9 105L10 105L10 104L9 104L9 103ZM27 103L27 104L28 104L28 103ZM44 103L44 105L45 105L45 103ZM0 104L0 107L1 107L1 104ZM20 104L20 105L21 105L21 104ZM76 104L76 105L75 105L75 107L76 107L76 105L77 105L77 104ZM88 104L88 105L89 105L89 104ZM15 105L15 106L16 106L16 105ZM27 105L27 106L28 106L28 105ZM2 106L2 108L1 108L1 109L3 109L3 106ZM39 106L39 107L40 107L40 106ZM97 106L97 107L95 107L95 108L94 108L94 109L93 109L93 110L96 110L96 111L95 111L95 112L97 112L97 111L98 111L98 109L99 109L99 108L100 108L100 107L98 107L98 106ZM6 107L6 108L7 108L7 107ZM11 107L11 108L12 108L12 107ZM97 107L97 109L98 109L98 107ZM102 107L102 109L103 109L103 107ZM108 107L108 108L107 108L107 109L106 109L106 110L105 110L105 109L104 109L104 110L105 110L105 111L103 111L103 112L105 112L105 111L107 111L107 110L108 110L108 108L109 108L109 107ZM115 107L115 108L114 108L114 109L113 109L113 111L114 111L114 110L116 110L116 109L115 109L115 108L116 108L116 107ZM95 108L95 109L96 109L96 108ZM31 109L31 112L34 112L34 109ZM41 109L41 110L42 110L42 109ZM57 109L57 112L60 112L60 109ZM70 109L70 110L72 110L72 111L69 111L69 114L71 114L71 113L70 113L70 112L72 112L72 114L73 114L73 115L71 115L71 117L72 117L72 116L74 116L74 117L76 117L76 116L74 116L74 114L76 114L76 113L77 113L77 112L76 112L76 113L74 113L74 114L73 114L73 111L74 111L74 110L73 110L73 109ZM79 109L79 110L80 110L80 112L81 112L81 110L80 110L80 109ZM83 109L83 112L86 112L86 109ZM109 109L109 112L112 112L112 109ZM9 110L9 111L10 111L10 112L9 112L9 113L11 113L11 111L10 111L10 110ZM18 110L18 111L17 111L17 114L16 114L16 115L19 115L19 116L20 116L20 115L19 115L19 112L18 112L18 111L19 111L19 110ZM28 110L28 111L29 111L29 110ZM32 110L32 111L33 111L33 110ZM51 110L51 111L52 111L52 110ZM55 110L55 111L56 111L56 110ZM58 110L58 111L59 111L59 110ZM84 110L84 111L85 111L85 110ZM110 110L110 111L111 111L111 110ZM20 111L20 114L21 114L21 111ZM115 111L115 112L116 112L116 111ZM12 112L12 113L14 113L14 114L13 114L13 115L12 115L12 114L9 114L9 116L10 116L10 115L12 115L12 116L13 116L13 115L14 115L14 116L15 116L15 115L14 115L14 114L15 114L15 112ZM65 112L65 113L64 113L64 114L66 114L66 112ZM107 112L107 113L104 113L104 114L105 114L105 115L107 115L107 114L109 114L109 115L110 115L110 116L114 116L114 115L113 115L113 114L111 114L111 113L108 113L108 112ZM113 112L113 113L114 113L114 112ZM30 113L30 114L31 114L31 113ZM32 113L32 114L34 114L34 115L35 115L35 113ZM48 113L48 115L49 115L49 113ZM85 113L85 114L86 114L86 113ZM36 114L36 115L37 115L37 114ZM45 114L45 115L46 115L46 116L47 116L47 114ZM110 114L110 115L111 115L111 114ZM59 115L59 116L60 116L60 115ZM62 115L62 116L63 116L63 117L64 117L64 116L63 116L63 115ZM66 115L66 116L67 116L67 115ZM90 115L90 116L91 116L91 115ZM102 115L102 116L103 116L103 115ZM77 116L77 117L78 117L78 116ZM87 116L87 117L88 117L88 116ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM117 0L110 0L110 7L117 7ZM116 1L111 1L111 6L116 6ZM115 2L112 2L112 5L115 5ZM0 117L7 117L7 110L0 110ZM1 116L6 116L6 111L1 111ZM2 115L5 115L5 112L2 112Z\" fill=\"#000000\"/></g></g></svg>\n",
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"512\" height=\"512\" viewBox=\"0 0 512 512\"><rect x=\"0\" y=\"0\" width=\"512\" height=\"512\" fill=\"#fefefe\"/><g transform=\"scale(3.436)\"><g transform=\"translate(16.000,16.000)\"><path fill-rule=\"evenodd\" d=\"M8 0L8 4L10 4L10 5L8 5L8 7L9 7L9 6L10 6L10 9L8 9L8 10L9 10L9 11L10 11L10 12L6 12L6 13L5 13L5 10L6 10L6 11L7 11L7 10L6 10L6 9L7 9L7 8L4 8L4 10L3 10L3 9L2 9L2 8L0 8L0 9L2 9L2 10L3 10L3 11L2 11L2 12L1 12L1 13L2 13L2 14L0 14L0 17L1 17L1 16L3 16L3 17L2 17L2 20L0 20L0 22L1 22L1 23L0 23L0 24L1 24L1 25L2 25L2 26L0 26L0 32L1 32L1 31L2 31L2 30L3 30L3 29L5 29L5 30L4 30L4 33L3 33L3 36L4 36L4 37L2 37L2 38L1 38L1 39L0 39L0 41L1 41L1 42L0 42L0 43L3 43L3 45L2 45L2 44L0 44L0 45L1 45L1 46L4 46L4 48L3 48L3 51L2 51L2 50L0 50L0 51L2 51L2 53L1 53L1 54L0 54L0 55L1 55L1 54L3 54L3 56L4 56L4 57L1 57L1 56L0 56L0 57L1 57L1 58L2 58L2 59L3 59L3 61L5 61L5 62L3 62L3 63L4 63L4 64L6 64L6 65L7 65L7 66L6 66L6 67L5 67L5 65L3 65L3 66L0 66L0 67L2 67L2 70L1 70L1 69L0 69L0 71L1 71L1 72L2 72L2 70L3 70L3 71L4 71L4 72L3 72L3 73L2 73L2 75L0 75L0 77L1 77L1 78L0 78L0 80L1 80L1 81L3 81L3 80L6 80L6 81L4 81L4 84L2 84L2 86L4 86L4 87L3 87L3 88L2 88L2 89L1 89L1 86L0 86L0 89L1 89L1 90L0 90L0 91L1 91L1 92L0 92L0 95L1 95L1 94L2 94L2 89L3 89L3 94L4 94L4 96L3 96L3 95L2 95L2 96L0 96L0 100L1 100L1 98L3 98L3 99L2 99L2 100L4 100L4 99L8 99L8 98L5 98L5 97L7 97L7 96L11 96L11 97L12 97L12 98L11 98L11 99L10 99L10 97L9 97L9 99L10 99L10 100L11 100L11 99L12 99L12 100L13 100L13 99L14 99L14 98L15 98L15 97L16 97L16 99L17 99L17 102L16 102L16 101L15 101L15 100L14 100L14 101L13 101L13 102L14 102L14 103L13 103L13 104L11 104L11 105L9 105L9 106L8 106L8 104L10 104L10 103L8 103L8 102L9 102L9 100L6 100L6 101L5 101L5 104L3 104L3 105L7 105L7 106L4 106L4 107L5 107L5 108L6 108L6 109L7 109L7 108L6 108L6 107L7 107L7 106L8 106L8 108L9 108L9 106L11 106L11 107L10 107L10 108L11 108L11 109L10 109L10 110L11 110L11 109L13 109L13 108L14 108L14 107L16 107L16 106L17 106L17 107L18 107L18 106L20 106L20 109L19 109L19 108L18 108L18 109L17 109L17 110L19 110L19 111L18 111L18 112L19 112L19 113L18 113L18 114L16 114L16 116L14 116L14 117L17 117L17 116L18 116L18 117L22 117L22 116L21 116L21 115L22 115L22 114L21 114L21 113L23 113L23 115L26 115L26 117L27 117L27 115L28 115L28 117L29 117L29 115L28 115L28 114L27 114L27 113L28 113L28 112L29 112L29 113L30 113L30 114L31 114L31 115L30 115L30 117L32 117L32 114L34 114L34 115L33 115L33 116L34 116L34 115L36 115L36 114L34 114L34 113L36 113L36 112L35 112L35 110L36 110L36 111L37 111L37 113L42 113L42 114L43 114L43 115L42 115L42 116L41 116L41 115L40 115L40 116L39 116L39 115L38 115L38 114L37 114L37 115L38 115L38 116L36 116L36 117L40 117L40 116L41 116L41 117L42 117L42 116L43 116L43 117L46 117L46 116L47 116L47 117L49 117L49 116L47 116L47 115L48 115L48 114L49 114L49 115L50 115L50 117L52 117L52 116L53 116L53 114L52 114L52 115L50 115L50 114L51 114L51 112L50 112L50 110L48 110L48 109L47 109L47 110L46 110L46 109L45 109L45 110L44 110L44 109L43 109L43 111L39 111L39 112L38 112L38 108L34 108L34 105L36 105L36 107L38 107L38 105L40 105L40 104L41 104L41 103L40 103L40 104L37 104L37 105L36 105L36 104L35 104L35 103L34 103L34 105L33 105L33 104L32 104L32 100L34 100L34 99L35 99L35 102L36 102L36 103L39 103L39 102L40 102L40 101L41 101L41 102L43 102L43 103L42 103L42 105L43 105L43 106L45 106L45 107L48 107L48 108L49 108L49 107L50 107L50 108L51 108L51 107L52 107L52 109L51 109L51 110L52 110L52 113L55 113L55 115L54 115L54 117L57 117L57 116L58 116L58 117L59 117L59 114L58 114L58 113L60 113L60 114L61 114L61 115L62 115L62 114L63 114L63 111L62 111L62 110L64 110L64 112L65 112L65 113L64 113L64 116L62 116L62 117L64 117L64 116L65 116L65 113L66 113L66 114L69 114L69 113L70 113L70 115L69 115L69 116L70 116L70 115L74 115L74 116L72 116L72 117L74 117L74 116L75 116L75 115L74 115L74 114L76 114L76 117L78 117L78 116L79 116L79 115L81 115L81 114L82 114L82 116L80 116L80 117L82 117L82 116L84 116L84 117L87 117L87 115L88 115L88 117L89 117L89 116L90 116L90 117L95 117L95 116L96 116L96 115L97 115L97 117L100 117L100 116L101 116L101 113L103 113L103 112L102 112L102 111L100 111L100 112L101 112L101 113L100 113L100 116L98 116L98 114L99 114L99 113L98 113L98 110L100 110L100 109L101 109L101 108L102 108L102 107L101 107L101 103L100 103L100 102L99 102L99 101L100 101L100 100L101 100L101 102L103 102L103 101L104 101L104 102L106 102L106 103L107 103L107 104L111 104L111 103L110 103L110 102L112 102L112 101L113 101L113 102L114 102L114 103L112 103L112 105L106 105L106 104L105 104L105 105L103 105L103 104L104 104L104 103L103 103L103 104L102 104L102 105L103 105L103 106L106 106L106 113L105 113L105 114L103 114L103 115L102 115L102 117L106 117L106 115L107 115L107 116L108 116L108 114L106 114L106 113L109 113L109 117L111 117L111 116L110 116L110 113L111 113L111 114L112 114L112 117L113 117L113 115L115 115L115 114L117 114L117 111L116 111L116 110L117 110L117 109L116 109L116 110L115 110L115 109L114 109L114 110L113 110L113 108L112 108L112 107L116 107L116 108L117 108L117 107L116 107L116 106L117 106L117 105L116 105L116 104L117 104L117 103L116 103L116 101L117 101L117 100L116 100L116 98L112 98L112 99L111 99L111 100L112 100L112 101L110 101L110 102L109 102L109 103L108 103L108 102L106 102L106 101L107 101L107 99L110 99L110 98L108 98L108 97L114 97L114 96L113 96L113 95L116 95L116 94L117 94L117 91L116 91L116 89L115 89L115 88L116 88L116 86L117 86L117 85L116 85L116 86L115 86L115 85L114 85L114 83L113 83L113 82L114 82L114 81L115 81L115 84L116 84L116 82L117 82L117 81L116 81L116 80L117 80L117 79L116 79L116 80L114 80L114 81L113 81L113 82L111 82L111 81L112 81L112 80L111 80L111 81L110 81L110 80L109 80L109 79L115 79L115 78L114 78L114 77L117 77L117 75L116 75L116 74L117 74L117 73L112 73L112 71L113 71L113 70L115 70L115 69L114 69L114 67L117 67L117 63L115 63L115 62L114 62L114 63L112 63L112 62L113 62L113 61L117 61L117 59L116 59L116 58L117 58L117 56L116 56L116 54L117 54L117 53L116 53L116 50L117 50L117 47L116 47L116 46L117 46L117 43L116 43L116 42L117 42L117 39L116 39L116 36L117 36L117 35L116 35L116 33L117 33L117 32L116 32L116 30L117 30L117 28L116 28L116 25L117 25L117 24L116 24L116 23L115 23L115 22L116 22L116 21L117 21L117 20L116 20L116 18L117 18L117 15L116 15L116 14L117 14L117 13L116 13L116 12L117 12L117 11L116 11L116 10L117 10L117 8L113 8L113 9L112 9L112 8L111 8L111 10L112 10L112 13L111 13L111 12L110 12L110 11L108 11L108 12L107 12L107 13L106 13L106 12L105 12L105 14L104 14L104 12L103 12L103 11L105 11L105 9L106 9L106 8L107 8L107 10L106 10L106 11L107 11L107 10L108 10L108 9L109 9L109 10L110 10L110 9L109 9L109 6L108 6L108 5L107 5L107 4L106 4L106 3L109 3L109 1L108 1L108 2L106 2L106 3L104 3L104 2L105 2L105 1L107 1L107 0L105 0L105 1L103 1L103 0L102 0L102 3L104 3L104 4L101 4L101 1L100 1L100 4L99 4L99 5L98 5L98 7L97 7L97 3L96 3L96 4L95 4L95 2L94 2L94 1L95 1L95 0L94 0L94 1L93 1L93 0L92 0L92 2L91 2L91 1L89 1L89 0L87 0L87 1L86 1L86 0L85 0L85 1L86 1L86 2L87 2L87 4L84 4L84 3L80 3L80 2L79 2L79 1L80 1L80 0L79 0L79 1L77 1L77 2L76 2L76 3L75 3L75 4L74 4L74 3L72 3L72 1L74 1L74 2L75 2L75 1L76 1L76 0L72 0L72 1L71 1L71 0L70 0L70 1L68 1L68 0L66 0L66 3L67 3L67 4L68 4L68 5L66 5L66 4L65 4L65 6L64 6L64 7L65 7L65 6L66 6L66 10L65 10L65 8L64 8L64 9L63 9L63 8L62 8L62 7L63 7L63 5L64 5L64 4L63 4L63 3L62 3L62 2L63 2L63 1L65 1L65 0L61 0L61 1L59 1L59 2L61 2L61 3L62 3L62 4L60 4L60 3L59 3L59 4L58 4L58 3L57 3L57 4L56 4L56 2L58 2L58 0L57 0L57 1L56 1L56 0L55 0L55 1L52 1L52 0L51 0L51 1L50 1L50 9L49 9L49 8L48 8L48 10L47 10L47 9L46 9L46 8L47 8L47 6L48 6L48 7L49 7L49 6L48 6L48 5L49 5L49 4L47 4L47 3L46 3L46 1L47 1L47 2L49 2L49 0L48 0L48 1L47 1L47 0L43 0L43 1L41 1L41 2L40 2L40 0L39 0L39 1L38 1L38 2L40 2L40 4L39 4L39 3L36 3L36 7L37 7L37 5L38 5L38 4L39 4L39 5L40 5L40 4L43 4L43 5L41 5L41 6L40 6L40 8L42 8L42 9L41 9L41 10L40 10L40 9L39 9L39 8L38 8L38 9L37 9L37 8L36 8L36 9L35 9L35 4L34 4L34 3L32 3L32 2L31 2L31 1L33 1L33 2L34 2L34 1L35 1L35 2L37 2L37 0L36 0L36 1L35 1L35 0L31 0L31 1L28 1L28 0L25 0L25 1L26 1L26 2L27 2L27 1L28 1L28 3L29 3L29 4L30 4L30 5L29 5L29 6L28 6L28 5L26 5L26 7L27 7L27 8L26 8L26 9L25 9L25 10L24 10L24 7L25 7L25 5L24 5L24 4L23 4L23 3L24 3L24 1L22 1L22 0L20 0L20 2L19 2L19 0L16 0L16 1L13 1L13 2L11 2L11 0L10 0L10 2L9 2L9 0ZM81 0L81 1L82 1L82 0ZM83 0L83 1L84 1L84 0ZM17 1L17 7L16 7L16 6L15 6L15 7L14 7L14 6L13 6L13 5L14 5L14 4L13 4L13 3L14 3L14 2L13 2L13 3L10 3L10 4L11 4L11 8L12 8L12 9L11 9L11 10L10 10L10 11L11 11L11 12L10 12L10 13L9 13L9 15L5 15L5 14L3 14L3 16L4 16L4 15L5 15L5 16L7 16L7 17L6 17L6 18L5 18L5 23L4 23L4 21L3 21L3 20L4 20L4 17L3 17L3 20L2 20L2 21L1 21L1 22L3 22L3 23L1 23L1 24L2 24L2 25L3 25L3 23L4 23L4 24L5 24L5 26L7 26L7 25L8 25L8 30L9 30L9 28L10 28L10 27L11 27L11 28L12 28L12 27L11 27L11 26L10 26L10 27L9 27L9 25L10 25L10 23L9 23L9 22L11 22L11 21L13 21L13 22L12 22L12 26L13 26L13 27L14 27L14 28L13 28L13 29L12 29L12 30L13 30L13 29L14 29L14 28L15 28L15 26L17 26L17 25L14 25L14 21L13 21L13 19L14 19L14 18L15 18L15 21L17 21L17 22L16 22L16 23L15 23L15 24L17 24L17 22L18 22L18 26L21 26L21 25L22 25L22 26L25 26L25 25L26 25L26 27L27 27L27 28L25 28L25 27L24 27L24 28L22 28L22 27L21 27L21 29L20 29L20 30L17 30L17 29L16 29L16 30L17 30L17 31L15 31L15 30L14 30L14 32L13 32L13 31L11 31L11 29L10 29L10 31L9 31L9 32L10 32L10 31L11 31L11 32L12 32L12 33L14 33L14 32L16 32L16 34L15 34L15 35L14 35L14 34L13 34L13 35L12 35L12 36L11 36L11 35L10 35L10 34L9 34L9 35L8 35L8 38L7 38L7 37L6 37L6 36L7 36L7 35L5 35L5 37L6 37L6 38L7 38L7 39L5 39L5 38L2 38L2 40L1 40L1 41L2 41L2 42L4 42L4 41L5 41L5 45L4 45L4 46L5 46L5 48L7 48L7 49L5 49L5 51L4 51L4 52L3 52L3 53L4 53L4 54L5 54L5 53L6 53L6 54L7 54L7 53L8 53L8 54L9 54L9 55L8 55L8 56L9 56L9 59L11 59L11 60L10 60L10 62L12 62L12 61L13 61L13 64L15 64L15 63L16 63L16 65L19 65L19 66L17 66L17 67L18 67L18 68L16 68L16 66L15 66L15 65L14 65L14 66L12 66L12 63L11 63L11 64L10 64L10 63L9 63L9 62L8 62L8 61L6 61L6 62L8 62L8 63L6 63L6 64L8 64L8 65L10 65L10 66L9 66L9 68L8 68L8 66L7 66L7 67L6 67L6 68L5 68L5 67L3 67L3 70L4 70L4 71L5 71L5 72L7 72L7 71L8 71L8 72L10 72L10 74L9 74L9 73L6 73L6 74L5 74L5 75L2 75L2 76L1 76L1 77L2 77L2 76L3 76L3 78L2 78L2 79L3 79L3 78L4 78L4 77L5 77L5 78L7 78L7 77L6 77L6 76L10 76L10 77L8 77L8 79L9 79L9 81L8 81L8 82L11 82L11 81L10 81L10 80L12 80L12 81L13 81L13 82L14 82L14 83L13 83L13 84L12 84L12 86L10 86L10 85L9 85L9 86L10 86L10 87L11 87L11 88L9 88L9 87L8 87L8 91L5 91L5 90L7 90L7 89L4 89L4 91L5 91L5 92L4 92L4 93L5 93L5 92L9 92L9 89L10 89L10 95L11 95L11 92L12 92L12 95L13 95L13 96L12 96L12 97L13 97L13 96L17 96L17 97L19 97L19 98L20 98L20 95L22 95L22 97L21 97L21 99L19 99L19 100L18 100L18 102L17 102L17 103L19 103L19 100L21 100L21 101L20 101L20 102L21 102L21 103L20 103L20 104L17 104L17 105L20 105L20 104L22 104L22 105L21 105L21 106L24 106L24 105L23 105L23 104L26 104L26 105L25 105L25 106L26 106L26 107L22 107L22 108L23 108L23 109L25 109L25 111L23 111L23 113L24 113L24 112L25 112L25 111L27 111L27 112L26 112L26 113L25 113L25 114L26 114L26 115L27 115L27 114L26 114L26 113L27 113L27 112L28 112L28 111L27 111L27 109L26 109L26 107L27 107L27 108L28 108L28 106L29 106L29 107L30 107L30 108L33 108L33 105L32 105L32 104L30 104L30 105L29 105L29 104L28 104L28 105L27 105L27 104L26 104L26 103L28 103L28 101L29 101L29 103L31 103L31 102L30 102L30 101L31 101L31 100L32 100L32 99L34 99L34 98L35 98L35 99L36 99L36 102L37 102L37 101L38 101L38 102L39 102L39 100L40 100L40 99L39 99L39 97L40 97L40 98L41 98L41 97L43 97L43 94L44 94L44 96L45 96L45 95L46 95L46 94L47 94L47 96L46 96L46 97L48 97L48 98L47 98L47 100L46 100L46 98L45 98L45 97L44 97L44 99L43 99L43 98L42 98L42 99L41 99L41 101L44 101L44 100L46 100L46 101L45 101L45 102L44 102L44 105L45 105L45 106L48 106L48 107L49 107L49 106L48 106L48 104L51 104L51 105L50 105L50 107L51 107L51 105L52 105L52 107L55 107L55 108L56 108L56 106L57 106L57 107L58 107L58 108L60 108L60 107L61 107L61 106L62 106L62 107L63 107L63 108L64 108L64 109L65 109L65 112L66 112L66 113L68 113L68 112L69 112L69 111L70 111L70 113L71 113L71 112L72 112L72 113L73 113L73 114L74 114L74 112L76 112L76 114L77 114L77 115L79 115L79 113L80 113L80 114L81 114L81 113L82 113L82 114L83 114L83 115L84 115L84 116L86 116L86 115L87 115L87 114L89 114L89 115L90 115L90 116L91 116L91 115L92 115L92 116L93 116L93 115L94 115L94 113L95 113L95 115L96 115L96 113L95 113L95 112L97 112L97 109L95 109L95 110L96 110L96 111L94 111L94 109L93 109L93 110L92 110L92 108L93 108L93 107L94 107L94 106L95 106L95 108L98 108L98 109L100 109L100 106L99 106L99 104L100 104L100 103L99 103L99 102L98 102L98 107L97 107L97 106L95 106L95 105L96 105L96 104L97 104L97 103L96 103L96 102L97 102L97 99L96 99L96 98L98 98L98 101L99 101L99 99L101 99L101 100L102 100L102 101L103 101L103 100L102 100L102 99L104 99L104 101L106 101L106 96L107 96L107 97L108 97L108 96L107 96L107 95L106 95L106 94L105 94L105 95L103 95L103 94L102 94L102 92L103 92L103 91L104 91L104 93L107 93L107 94L108 94L108 95L109 95L109 94L110 94L110 95L112 95L112 94L114 94L114 93L115 93L115 92L116 92L116 91L114 91L114 93L113 93L113 91L112 91L112 90L114 90L114 88L115 88L115 86L114 86L114 85L113 85L113 87L111 87L111 88L113 88L113 89L110 89L110 91L112 91L112 94L111 94L111 92L110 92L110 93L109 93L109 92L108 92L108 91L109 91L109 89L108 89L108 87L107 87L107 88L106 88L106 86L108 86L108 85L107 85L107 84L106 84L106 82L107 82L107 80L105 80L105 79L106 79L106 78L107 78L107 79L109 79L109 78L107 78L107 77L108 77L108 76L110 76L110 77L111 77L111 76L112 76L112 77L113 77L113 76L115 76L115 75L114 75L114 74L113 74L113 75L112 75L112 74L110 74L110 72L109 72L109 71L110 71L110 69L113 69L113 67L114 67L114 65L115 65L115 63L114 63L114 65L113 65L113 64L112 64L112 65L113 65L113 66L110 66L110 65L111 65L111 63L110 63L110 61L109 61L109 62L107 62L107 63L106 63L106 61L108 61L108 60L107 60L107 59L108 59L108 56L105 56L105 55L106 55L106 54L105 54L105 51L106 51L106 52L107 52L107 51L106 51L106 50L108 50L108 49L109 49L109 50L110 50L110 52L109 52L109 53L108 53L108 54L107 54L107 55L108 55L108 54L109 54L109 55L110 55L110 56L112 56L112 55L113 55L113 60L115 60L115 59L114 59L114 57L116 57L116 56L115 56L115 54L116 54L116 53L114 53L114 52L115 52L115 50L113 50L113 49L110 49L110 48L112 48L112 47L111 47L111 45L112 45L112 43L110 43L110 44L109 44L109 43L108 43L108 44L107 44L107 43L103 43L103 41L102 41L102 40L105 40L105 39L106 39L106 41L107 41L107 42L108 42L108 41L107 41L107 39L106 39L106 38L108 38L108 37L110 37L110 38L109 38L109 39L110 39L110 40L109 40L109 42L110 42L110 40L111 40L111 42L113 42L113 45L114 45L114 44L115 44L115 45L116 45L116 43L115 43L115 41L116 41L116 40L115 40L115 35L114 35L114 36L113 36L113 37L112 37L112 35L108 35L108 34L107 34L107 35L108 35L108 37L107 37L107 36L106 36L106 37L105 37L105 35L106 35L106 33L108 33L108 32L107 32L107 31L108 31L108 29L106 29L106 28L105 28L105 27L106 27L106 26L107 26L107 25L106 25L106 24L109 24L109 25L108 25L108 28L109 28L109 30L113 30L113 33L114 33L114 32L115 32L115 33L116 33L116 32L115 32L115 30L114 30L114 29L113 29L113 28L115 28L115 25L113 25L113 24L115 24L115 23L114 23L114 21L113 21L113 20L115 20L115 19L113 19L113 20L111 20L111 19L112 19L112 18L113 18L113 17L114 17L114 18L115 18L115 17L114 17L114 16L115 16L115 14L116 14L116 13L115 13L115 11L114 11L114 10L113 10L113 11L114 11L114 13L115 13L115 14L113 14L113 17L112 17L112 16L111 16L111 13L110 13L110 14L108 14L108 15L106 15L106 14L105 14L105 15L104 15L104 16L103 16L103 17L102 17L102 16L100 16L100 17L102 17L102 18L100 18L100 19L99 19L99 15L103 15L103 13L102 13L102 11L103 11L103 10L102 10L102 9L105 9L105 7L106 7L106 6L105 6L105 7L104 7L104 6L103 6L103 7L104 7L104 8L102 8L102 5L101 5L101 9L100 9L100 5L99 5L99 9L97 9L97 7L96 7L96 5L95 5L95 4L94 4L94 6L93 6L93 5L91 5L91 7L92 7L92 9L95 9L95 11L96 11L96 12L94 12L94 13L96 13L96 12L97 12L97 13L98 13L98 16L97 16L97 15L95 15L95 16L93 16L93 15L94 15L94 14L93 14L93 15L92 15L92 14L91 14L91 13L92 13L92 12L93 12L93 11L94 11L94 10L93 10L93 11L91 11L91 10L88 10L88 9L90 9L90 8L88 8L88 5L89 5L89 7L90 7L90 4L91 4L91 3L88 3L88 4L87 4L87 10L86 10L86 9L85 9L85 11L84 11L84 9L83 9L83 10L82 10L82 9L81 9L81 10L80 10L80 8L81 8L81 7L82 7L82 6L81 6L81 5L80 5L80 6L79 6L79 7L78 7L78 6L77 6L77 8L76 8L76 5L79 5L79 4L77 4L77 3L76 3L76 5L75 5L75 8L74 8L74 9L73 9L73 7L74 7L74 5L73 5L73 7L72 7L72 6L71 6L71 5L70 5L70 6L69 6L69 5L68 5L68 6L67 6L67 7L68 7L68 9L69 9L69 10L71 10L71 11L68 11L68 10L66 10L66 11L65 11L65 10L62 10L62 9L57 9L57 10L56 10L56 9L55 9L55 10L54 10L54 9L53 9L53 8L52 8L52 6L53 6L53 7L54 7L54 8L56 8L56 5L55 5L55 7L54 7L54 6L53 6L53 5L54 5L54 4L55 4L55 2L56 2L56 1L55 1L55 2L53 2L53 3L52 3L52 5L51 5L51 8L52 8L52 9L50 9L50 11L49 11L49 10L48 10L48 11L46 11L46 9L45 9L45 11L44 11L44 9L43 9L43 10L41 10L41 13L42 13L42 12L43 12L43 14L41 14L41 16L40 16L40 15L39 15L39 12L40 12L40 11L39 11L39 10L38 10L38 11L36 11L36 10L37 10L37 9L36 9L36 10L35 10L35 12L34 12L34 10L33 10L33 9L32 9L32 10L31 10L31 9L29 9L29 8L28 8L28 9L26 9L26 10L25 10L25 11L24 11L24 10L22 10L22 9L21 9L21 8L22 8L22 6L23 6L23 7L24 7L24 6L23 6L23 5L21 5L21 7L20 7L20 4L22 4L22 2L20 2L20 4L19 4L19 3L18 3L18 1ZM43 1L43 3L44 3L44 4L45 4L45 2L44 2L44 1ZM61 1L61 2L62 2L62 1ZM67 1L67 2L68 2L68 4L70 4L70 3L69 3L69 2L68 2L68 1ZM70 1L70 2L71 2L71 1ZM96 1L96 2L97 2L97 1ZM98 1L98 2L99 2L99 1ZM29 2L29 3L30 3L30 4L31 4L31 3L30 3L30 2ZM64 2L64 3L65 3L65 2ZM92 2L92 4L93 4L93 2ZM25 3L25 4L26 4L26 3ZM15 4L15 5L16 5L16 4ZM46 4L46 6L45 6L45 5L43 5L43 8L45 8L45 7L46 7L46 6L47 6L47 4ZM105 4L105 5L106 5L106 4ZM18 5L18 7L19 7L19 8L20 8L20 7L19 7L19 5ZM31 5L31 8L34 8L34 5ZM57 5L57 8L60 8L60 5ZM61 5L61 7L62 7L62 5ZM83 5L83 8L86 8L86 5ZM12 6L12 7L13 7L13 6ZM27 6L27 7L28 7L28 6ZM29 6L29 7L30 7L30 6ZM32 6L32 7L33 7L33 6ZM38 6L38 7L39 7L39 6ZM41 6L41 7L42 7L42 6ZM44 6L44 7L45 7L45 6ZM58 6L58 7L59 7L59 6ZM68 6L68 7L69 7L69 6ZM70 6L70 7L71 7L71 6ZM80 6L80 7L79 7L79 8L80 8L80 7L81 7L81 6ZM84 6L84 7L85 7L85 6ZM92 6L92 7L93 7L93 6ZM94 6L94 8L95 8L95 9L96 9L96 11L98 11L98 12L99 12L99 11L98 11L98 10L97 10L97 9L96 9L96 8L95 8L95 6ZM107 6L107 7L108 7L108 6ZM15 7L15 8L14 8L14 9L13 9L13 10L15 10L15 8L16 8L16 7ZM75 8L75 11L74 11L74 10L73 10L73 9L72 9L72 10L73 10L73 11L74 11L74 13L72 13L72 15L74 15L74 13L75 13L75 11L76 11L76 13L77 13L77 11L78 11L78 13L79 13L79 14L81 14L81 15L82 15L82 16L83 16L83 17L81 17L81 16L80 16L80 15L77 15L77 14L75 14L75 15L76 15L76 16L74 16L74 17L73 17L73 16L72 16L72 17L73 17L73 18L71 18L71 17L69 17L69 16L70 16L70 15L71 15L71 14L70 14L70 15L69 15L69 16L68 16L68 17L67 17L67 15L68 15L68 13L65 13L65 11L64 11L64 14L63 14L63 15L62 15L62 12L63 12L63 11L61 11L61 13L60 13L60 12L59 12L59 10L58 10L58 15L57 15L57 17L56 17L56 16L54 16L54 15L53 15L53 14L57 14L57 13L56 13L56 12L57 12L57 11L56 11L56 12L55 12L55 11L54 11L54 10L53 10L53 9L52 9L52 10L53 10L53 11L52 11L52 13L51 13L51 14L52 14L52 17L51 17L51 16L50 16L50 17L49 17L49 16L47 16L47 15L50 15L50 14L48 14L48 13L46 13L46 11L45 11L45 12L44 12L44 11L43 11L43 12L44 12L44 14L43 14L43 15L42 15L42 16L43 16L43 17L44 17L44 19L43 19L43 22L44 22L44 23L45 23L45 22L46 22L46 23L47 23L47 22L49 22L49 23L50 23L50 22L49 22L49 21L50 21L50 20L47 20L47 19L50 19L50 18L52 18L52 19L51 19L51 21L52 21L52 20L53 20L53 18L52 18L52 17L53 17L53 16L54 16L54 17L56 17L56 19L54 19L54 21L53 21L53 22L52 22L52 25L51 25L51 24L49 24L49 25L51 25L51 27L50 27L50 26L46 26L46 24L43 24L43 25L42 25L42 23L40 23L40 24L41 24L41 25L40 25L40 26L43 26L43 28L44 28L44 25L45 25L45 26L46 26L46 27L50 27L50 28L51 28L51 29L52 29L52 28L53 28L53 29L57 29L57 30L56 30L56 31L54 31L54 33L53 33L53 30L52 30L52 32L50 32L50 33L49 33L49 32L47 32L47 31L51 31L51 30L50 30L50 29L49 29L49 28L47 28L47 29L46 29L46 28L45 28L45 30L44 30L44 31L40 31L40 33L39 33L39 31L38 31L38 29L39 29L39 28L35 28L35 29L36 29L36 30L34 30L34 29L32 29L32 25L34 25L34 27L33 27L33 28L34 28L34 27L35 27L35 26L36 26L36 27L38 27L38 22L40 22L40 21L41 21L41 18L42 18L42 17L40 17L40 16L39 16L39 17L38 17L38 12L39 12L39 11L38 11L38 12L37 12L37 13L36 13L36 12L35 12L35 13L34 13L34 12L32 12L32 11L33 11L33 10L32 10L32 11L31 11L31 10L30 10L30 11L28 11L28 12L27 12L27 11L25 11L25 13L24 13L24 12L22 12L22 13L23 13L23 14L22 14L22 15L24 15L24 14L25 14L25 15L26 15L26 14L25 14L25 13L29 13L29 14L27 14L27 15L28 15L28 16L27 16L27 17L26 17L26 16L24 16L24 18L25 18L25 19L24 19L24 20L25 20L25 21L26 21L26 22L25 22L25 23L26 23L26 22L27 22L27 23L28 23L28 24L26 24L26 25L28 25L28 26L27 26L27 27L28 27L28 28L29 28L29 29L26 29L26 30L27 30L27 31L25 31L25 30L24 30L24 29L25 29L25 28L24 28L24 29L22 29L22 30L24 30L24 31L22 31L22 32L21 32L21 33L20 33L20 32L19 32L19 31L18 31L18 32L19 32L19 33L18 33L18 34L19 34L19 36L21 36L21 37L25 37L25 39L24 39L24 38L20 38L20 37L19 37L19 38L18 38L18 40L17 40L17 39L16 39L16 40L17 40L17 41L16 41L16 42L17 42L17 43L16 43L16 44L14 44L14 43L13 43L13 42L12 42L12 41L13 41L13 40L12 40L12 39L13 39L13 37L11 37L11 36L10 36L10 37L9 37L9 38L11 38L11 40L10 40L10 41L11 41L11 42L10 42L10 44L9 44L9 43L8 43L8 42L6 42L6 43L7 43L7 44L6 44L6 45L8 45L8 48L9 48L9 51L8 51L8 52L10 52L10 53L9 53L9 54L10 54L10 53L11 53L11 52L14 52L14 53L12 53L12 54L11 54L11 55L12 55L12 56L11 56L11 57L10 57L10 58L11 58L11 57L13 57L13 56L14 56L14 57L15 57L15 58L13 58L13 60L14 60L14 59L17 59L17 58L16 58L16 57L19 57L19 59L21 59L21 60L20 60L20 62L21 62L21 61L22 61L22 63L19 63L19 62L18 62L18 63L17 63L17 64L22 64L22 63L24 63L24 62L23 62L23 61L24 61L24 60L25 60L25 59L26 59L26 58L29 58L29 57L30 57L30 59L28 59L28 60L29 60L29 61L28 61L28 64L27 64L27 66L28 66L28 69L29 69L29 70L31 70L31 69L32 69L32 71L28 71L28 72L26 72L26 69L27 69L27 67L24 67L24 66L23 66L23 65L22 65L22 66L21 66L21 67L20 67L20 66L19 66L19 67L20 67L20 68L18 68L18 69L16 69L16 68L15 68L15 67L14 67L14 68L13 68L13 67L12 67L12 66L10 66L10 67L12 67L12 68L11 68L11 70L9 70L9 71L12 71L12 74L14 74L14 73L13 73L13 72L17 72L17 70L19 70L19 71L20 71L20 72L18 72L18 73L16 73L16 74L15 74L15 77L13 77L13 76L14 76L14 75L11 75L11 74L10 74L10 75L11 75L11 77L10 77L10 78L9 78L9 79L13 79L13 78L15 78L15 77L17 77L17 76L18 76L18 73L21 73L21 74L23 74L23 75L25 75L25 76L26 76L26 77L25 77L25 80L24 80L24 79L23 79L23 78L22 78L22 79L20 79L20 74L19 74L19 79L18 79L18 78L16 78L16 79L18 79L18 80L17 80L17 81L18 81L18 82L19 82L19 83L20 83L20 82L19 82L19 81L20 81L20 80L21 80L21 84L20 84L20 85L18 85L18 83L15 83L15 85L14 85L14 84L13 84L13 85L14 85L14 87L13 87L13 86L12 86L12 87L13 87L13 88L11 88L11 89L14 89L14 90L13 90L13 91L14 91L14 93L15 93L15 94L14 94L14 95L15 95L15 94L16 94L16 95L17 95L17 96L18 96L18 95L19 95L19 94L22 94L22 95L24 95L24 96L26 96L26 97L25 97L25 98L24 98L24 97L22 97L22 100L23 100L23 103L24 103L24 102L25 102L25 103L26 103L26 102L27 102L27 101L28 101L28 100L29 100L29 101L30 101L30 99L32 99L32 98L34 98L34 97L35 97L35 98L36 98L36 99L37 99L37 100L38 100L38 98L37 98L37 97L39 97L39 95L40 95L40 96L41 96L41 95L42 95L42 94L43 94L43 92L44 92L44 91L45 91L45 94L46 94L46 93L47 93L47 94L48 94L48 92L47 92L47 91L50 91L50 90L52 90L52 92L53 92L53 90L52 90L52 89L53 89L53 87L54 87L54 88L55 88L55 87L54 87L54 86L56 86L56 88L58 88L58 89L57 89L57 90L58 90L58 89L59 89L59 91L60 91L60 90L61 90L61 91L63 91L63 90L64 90L64 92L63 92L63 93L62 93L62 94L61 94L61 95L62 95L62 96L63 96L63 95L64 95L64 97L62 97L62 98L61 98L61 97L59 97L59 94L60 94L60 93L61 93L61 92L60 92L60 93L59 93L59 92L58 92L58 93L57 93L57 96L56 96L56 95L55 95L55 96L56 96L56 97L55 97L55 99L56 99L56 98L57 98L57 102L56 102L56 100L55 100L55 103L51 103L51 104L52 104L52 105L53 105L53 104L55 104L55 105L54 105L54 106L56 106L56 103L57 103L57 104L58 104L58 105L57 105L57 106L58 106L58 105L59 105L59 106L60 106L60 104L61 104L61 105L62 105L62 106L63 106L63 107L64 107L64 108L65 108L65 109L66 109L66 108L67 108L67 111L66 111L66 112L67 112L67 111L68 111L68 107L69 107L69 105L70 105L70 108L69 108L69 110L70 110L70 111L71 111L71 110L73 110L73 112L74 112L74 110L75 110L75 111L76 111L76 112L77 112L77 113L78 113L78 112L80 112L80 113L81 113L81 112L82 112L82 113L83 113L83 114L84 114L84 115L86 115L86 114L87 114L87 113L88 113L88 112L89 112L89 114L90 114L90 113L91 113L91 112L92 112L92 114L93 114L93 113L94 113L94 111L93 111L93 112L92 112L92 110L91 110L91 108L92 108L92 107L93 107L93 106L94 106L94 105L93 105L93 104L95 104L95 102L94 102L94 103L93 103L93 104L92 104L92 102L93 102L93 100L92 100L92 98L93 98L93 96L94 96L94 100L95 100L95 101L96 101L96 99L95 99L95 98L96 98L96 96L94 96L94 95L97 95L97 97L98 97L98 98L99 98L99 95L100 95L100 97L101 97L101 99L102 99L102 96L103 96L103 95L102 95L102 94L100 94L100 92L101 92L101 90L104 90L104 91L108 91L108 90L106 90L106 88L104 88L104 87L102 87L102 89L101 89L101 87L100 87L100 89L99 89L99 87L98 87L98 86L100 86L100 85L101 85L101 86L106 86L106 84L105 84L105 85L104 85L104 80L103 80L103 81L102 81L102 79L101 79L101 78L102 78L102 77L103 77L103 79L105 79L105 78L104 78L104 77L106 77L106 76L108 76L108 75L110 75L110 74L109 74L109 72L108 72L108 71L109 71L109 69L108 69L108 68L109 68L109 67L110 67L110 66L109 66L109 65L110 65L110 64L109 64L109 63L108 63L108 64L107 64L107 65L106 65L106 66L105 66L105 64L104 64L104 63L105 63L105 61L106 61L106 59L107 59L107 57L106 57L106 59L105 59L105 58L104 58L104 57L105 57L105 56L104 56L104 55L103 55L103 56L102 56L102 55L101 55L101 54L100 54L100 52L102 52L102 53L103 53L103 51L105 51L105 50L106 50L106 48L110 48L110 47L109 47L109 46L110 46L110 45L111 45L111 44L110 44L110 45L107 45L107 44L106 44L106 48L105 48L105 49L104 49L104 50L103 50L103 51L102 51L102 49L103 49L103 48L104 48L104 47L105 47L105 45L104 45L104 46L103 46L103 47L102 47L102 49L100 49L100 48L101 48L101 46L102 46L102 45L101 45L101 44L102 44L102 43L99 43L99 44L98 44L98 42L95 42L95 41L98 41L98 40L99 40L99 39L98 39L98 36L99 36L99 38L100 38L100 40L101 40L101 38L103 38L103 39L105 39L105 37L104 37L104 36L103 36L103 35L105 35L105 33L104 33L104 31L102 31L102 32L103 32L103 35L100 35L100 34L101 34L101 33L99 33L99 31L100 31L100 32L101 32L101 31L100 31L100 29L101 29L101 30L104 30L104 29L103 29L103 28L104 28L104 25L105 25L105 26L106 26L106 25L105 25L105 24L106 24L106 23L107 23L107 21L108 21L108 23L111 23L111 25L109 25L109 27L111 27L111 29L112 29L112 27L114 27L114 26L112 26L112 27L111 27L111 25L112 25L112 23L113 23L113 21L109 21L109 19L108 19L108 18L110 18L110 15L108 15L108 17L107 17L107 18L106 18L106 15L105 15L105 17L104 17L104 18L106 18L106 19L105 19L105 20L104 20L104 19L100 19L100 20L102 20L102 22L103 22L103 23L101 23L101 21L100 21L100 24L101 24L101 25L100 25L100 28L99 28L99 27L98 27L98 25L99 25L99 23L98 23L98 22L99 22L99 21L98 21L98 20L99 20L99 19L98 19L98 18L96 18L96 17L97 17L97 16L96 16L96 17L93 17L93 16L92 16L92 15L90 15L90 14L89 14L89 13L91 13L91 12L90 12L90 11L88 11L88 10L87 10L87 11L85 11L85 13L82 13L82 11L81 11L81 12L80 12L80 11L78 11L78 10L77 10L77 9L76 9L76 8ZM18 9L18 10L16 10L16 14L15 14L15 13L14 13L14 12L15 12L15 11L12 11L12 13L13 13L13 14L14 14L14 15L11 15L11 14L10 14L10 16L8 16L8 17L7 17L7 18L6 18L6 19L7 19L7 20L6 20L6 21L10 21L10 20L12 20L12 19L13 19L13 17L14 17L14 15L16 15L16 16L15 16L15 17L16 17L16 20L17 20L17 21L18 21L18 22L21 22L21 19L20 19L20 16L21 16L21 18L22 18L22 20L23 20L23 17L22 17L22 16L21 16L21 15L20 15L20 16L19 16L19 15L18 15L18 14L19 14L19 13L20 13L20 14L21 14L21 13L20 13L20 12L19 12L19 9ZM20 9L20 10L21 10L21 11L22 11L22 10L21 10L21 9ZM100 10L100 12L101 12L101 11L102 11L102 10ZM3 11L3 12L2 12L2 13L3 13L3 12L4 12L4 11ZM30 11L30 12L31 12L31 11ZM48 11L48 12L49 12L49 11ZM50 11L50 12L51 12L51 11ZM53 11L53 13L54 13L54 11ZM66 11L66 12L68 12L68 11ZM83 11L83 12L84 12L84 11ZM87 11L87 12L86 12L86 13L87 13L87 12L88 12L88 13L89 13L89 12L88 12L88 11ZM18 12L18 13L17 13L17 14L18 14L18 13L19 13L19 12ZM69 12L69 13L70 13L70 12ZM79 12L79 13L80 13L80 12ZM6 13L6 14L7 14L7 13ZM30 13L30 15L29 15L29 16L31 16L31 17L27 17L27 18L26 18L26 19L25 19L25 20L26 20L26 21L27 21L27 22L28 22L28 23L29 23L29 24L28 24L28 25L30 25L30 24L31 24L31 25L32 25L32 24L34 24L34 25L35 25L35 24L37 24L37 23L36 23L36 21L37 21L37 22L38 22L38 20L37 20L37 19L39 19L39 21L40 21L40 17L39 17L39 18L38 18L38 17L37 17L37 15L34 15L34 13ZM59 13L59 15L58 15L58 17L57 17L57 19L58 19L58 17L59 17L59 16L62 16L62 17L63 17L63 18L62 18L62 19L61 19L61 18L60 18L60 19L59 19L59 21L57 21L57 22L56 22L56 20L55 20L55 22L54 22L54 23L53 23L53 24L55 24L55 25L53 25L53 26L52 26L52 27L51 27L51 28L52 28L52 27L53 27L53 28L57 28L57 29L58 29L58 30L59 30L59 29L60 29L60 30L61 30L61 29L63 29L63 28L64 28L64 27L65 27L65 28L66 28L66 27L67 27L67 25L70 25L70 26L71 26L71 28L70 28L70 27L69 27L69 26L68 26L68 28L67 28L67 29L66 29L66 30L65 30L65 29L64 29L64 31L63 31L63 30L62 30L62 32L63 32L63 33L62 33L62 35L61 35L61 36L62 36L62 35L63 35L63 38L61 38L61 39L60 39L60 40L61 40L61 39L63 39L63 42L62 42L62 41L61 41L61 42L60 42L60 41L58 41L58 37L59 37L59 36L58 36L58 37L57 37L57 36L56 36L56 32L55 32L55 33L54 33L54 35L53 35L53 36L54 36L54 37L55 37L55 38L54 38L54 39L56 39L56 40L57 40L57 41L58 41L58 42L59 42L59 44L58 44L58 43L57 43L57 44L56 44L56 47L55 47L55 45L54 45L54 44L53 44L53 45L52 45L52 46L51 46L51 45L50 45L50 44L52 44L52 43L51 43L51 42L52 42L52 41L53 41L53 43L55 43L55 42L54 42L54 41L55 41L55 40L54 40L54 41L53 41L53 40L52 40L52 39L51 39L51 38L52 38L52 37L50 37L50 36L49 36L49 35L51 35L51 36L52 36L52 34L53 34L53 33L52 33L52 34L48 34L48 33L44 33L44 36L45 36L45 37L44 37L44 38L45 38L45 37L46 37L46 38L47 38L47 40L46 40L46 39L43 39L43 37L42 37L42 38L41 38L41 39L42 39L42 40L40 40L40 37L41 37L41 36L42 36L42 35L43 35L43 34L42 34L42 33L43 33L43 32L41 32L41 33L40 33L40 34L38 34L38 35L37 35L37 34L36 34L36 35L35 35L35 37L33 37L33 35L31 35L31 36L28 36L28 35L29 35L29 33L30 33L30 29L29 29L29 30L28 30L28 31L27 31L27 32L28 32L28 33L27 33L27 34L26 34L26 33L25 33L25 34L26 34L26 35L25 35L25 36L26 36L26 37L27 37L27 38L26 38L26 40L25 40L25 41L23 41L23 40L24 40L24 39L22 39L22 41L23 41L23 44L24 44L24 45L23 45L23 46L24 46L24 47L23 47L23 48L22 48L22 47L17 47L17 46L18 46L18 45L19 45L19 46L20 46L20 45L21 45L21 46L22 46L22 44L20 44L20 43L19 43L19 44L18 44L18 43L17 43L17 44L16 44L16 45L15 45L15 46L16 46L16 47L15 47L15 48L16 48L16 49L15 49L15 51L14 51L14 52L15 52L15 53L16 53L16 56L15 56L15 54L14 54L14 56L15 56L15 57L16 57L16 56L17 56L17 55L18 55L18 56L19 56L19 55L22 55L22 56L20 56L20 57L24 57L24 59L23 59L23 58L22 58L22 61L23 61L23 60L24 60L24 59L25 59L25 58L26 58L26 57L25 57L25 56L27 56L27 57L28 57L28 56L29 56L29 55L27 55L27 54L30 54L30 55L31 55L31 56L34 56L34 54L35 54L35 53L34 53L34 54L33 54L33 53L32 53L32 51L33 51L33 49L34 49L34 50L35 50L35 49L39 49L39 50L38 50L38 51L39 51L39 52L40 52L40 53L39 53L39 54L38 54L38 53L37 53L37 55L35 55L35 56L37 56L37 59L36 59L36 57L35 57L35 59L36 59L36 60L37 60L37 59L39 59L39 60L40 60L40 61L38 61L38 63L39 63L39 64L38 64L38 65L37 65L37 61L33 61L33 63L30 63L30 64L33 64L33 65L35 65L35 64L36 64L36 65L37 65L37 66L36 66L36 67L35 67L35 68L34 68L34 67L33 67L33 68L32 68L32 69L33 69L33 71L32 71L32 73L31 73L31 76L30 76L30 72L28 72L28 75L27 75L27 73L26 73L26 74L25 74L25 71L24 71L24 70L23 70L23 69L26 69L26 68L24 68L24 67L23 67L23 66L22 66L22 67L21 67L21 68L22 68L22 70L23 70L23 71L24 71L24 74L25 74L25 75L27 75L27 77L26 77L26 81L25 81L25 82L26 82L26 85L28 85L28 86L26 86L26 87L25 87L25 86L24 86L24 85L25 85L25 83L22 83L22 84L24 84L24 85L22 85L22 86L23 86L23 88L27 88L27 89L28 89L28 90L26 90L26 89L25 89L25 91L24 91L24 90L23 90L23 89L22 89L22 87L20 87L20 86L21 86L21 85L20 85L20 86L18 86L18 87L19 87L19 89L18 89L18 88L17 88L17 89L16 89L16 87L17 87L17 85L16 85L16 86L15 86L15 87L14 87L14 88L15 88L15 89L16 89L16 90L14 90L14 91L16 91L16 92L17 92L17 93L16 93L16 94L18 94L18 93L19 93L19 92L20 92L20 91L21 91L21 92L22 92L22 91L21 91L21 89L22 89L22 90L23 90L23 93L24 93L24 95L27 95L27 96L28 96L28 98L26 98L26 99L23 99L23 100L26 100L26 101L27 101L27 100L28 100L28 98L29 98L29 99L30 99L30 97L31 97L31 96L30 96L30 97L29 97L29 96L28 96L28 95L32 95L32 96L33 96L33 97L34 97L34 96L36 96L36 97L37 97L37 96L38 96L38 95L39 95L39 94L38 94L38 92L37 92L37 91L39 91L39 93L40 93L40 92L41 92L41 93L42 93L42 91L44 91L44 90L45 90L45 91L46 91L46 90L45 90L45 87L47 87L47 86L46 86L46 85L44 85L44 86L45 86L45 87L43 87L43 86L41 86L41 87L43 87L43 88L40 88L40 85L43 85L43 84L45 84L45 83L46 83L46 84L47 84L47 85L48 85L48 84L49 84L49 85L52 85L52 83L51 83L51 82L53 82L53 83L55 83L55 84L54 84L54 85L56 85L56 80L55 80L55 79L57 79L57 80L58 80L58 81L61 81L61 82L62 82L62 83L61 83L61 84L62 84L62 83L63 83L63 84L64 84L64 85L63 85L63 86L61 86L61 87L63 87L63 88L60 88L60 87L59 87L59 89L62 89L62 90L63 90L63 88L64 88L64 87L65 87L65 89L64 89L64 90L65 90L65 92L64 92L64 93L66 93L66 91L68 91L68 92L67 92L67 93L68 93L68 94L64 94L64 95L68 95L68 96L67 96L67 97L66 97L66 96L65 96L65 97L64 97L64 98L62 98L62 100L61 100L61 101L58 101L58 103L61 103L61 102L62 102L62 105L64 105L64 104L66 104L66 103L67 103L67 102L68 102L68 105L65 105L65 106L64 106L64 107L68 107L68 105L69 105L69 104L72 104L72 105L74 105L74 106L72 106L72 107L71 107L71 108L70 108L70 109L71 109L71 108L72 108L72 109L74 109L74 108L73 108L73 107L77 107L77 108L75 108L75 109L76 109L76 111L80 111L80 112L81 112L81 111L82 111L82 110L80 110L80 109L79 109L79 110L78 110L78 109L77 109L77 108L79 108L79 107L80 107L80 108L81 108L81 106L82 106L82 108L84 108L84 107L85 107L85 108L86 108L86 107L85 107L85 105L86 105L86 104L88 104L88 106L87 106L87 108L88 108L88 109L87 109L87 110L88 110L88 111L87 111L87 112L88 112L88 111L89 111L89 112L91 112L91 110L90 110L90 109L89 109L89 108L90 108L90 107L91 107L91 106L90 106L90 107L89 107L89 108L88 108L88 106L89 106L89 105L90 105L90 104L91 104L91 105L92 105L92 104L91 104L91 102L89 102L89 103L90 103L90 104L88 104L88 103L87 103L87 102L88 102L88 101L92 101L92 100L91 100L91 94L89 94L89 92L88 92L88 91L90 91L90 93L92 93L92 96L93 96L93 95L94 95L94 94L93 94L93 93L92 93L92 92L95 92L95 91L96 91L96 93L95 93L95 94L96 94L96 93L97 93L97 92L98 92L98 93L99 93L99 92L100 92L100 91L99 91L99 90L98 90L98 91L96 91L96 89L97 89L97 88L98 88L98 87L97 87L97 86L96 86L96 85L95 85L95 84L98 84L98 85L99 85L99 84L100 84L100 82L99 82L99 81L98 81L98 83L97 83L97 79L95 79L95 80L94 80L94 78L95 78L95 77L96 77L96 76L97 76L97 77L98 77L98 76L99 76L99 75L101 75L101 76L100 76L100 77L99 77L99 78L98 78L98 79L99 79L99 80L100 80L100 79L99 79L99 78L101 78L101 77L102 77L102 75L104 75L104 76L103 76L103 77L104 77L104 76L105 76L105 75L106 75L106 74L105 74L105 75L104 75L104 73L100 73L100 71L101 71L101 72L102 72L102 71L104 71L104 72L105 72L105 73L106 73L106 72L107 72L107 73L108 73L108 72L107 72L107 71L108 71L108 69L106 69L106 68L108 68L108 67L109 67L109 66L108 66L108 67L104 67L104 64L103 64L103 62L104 62L104 61L105 61L105 59L104 59L104 60L103 60L103 57L104 57L104 56L103 56L103 57L102 57L102 58L101 58L101 57L100 57L100 55L99 55L99 53L98 53L98 56L97 56L97 55L96 55L96 52L99 52L99 50L100 50L100 51L101 51L101 50L100 50L100 49L99 49L99 48L100 48L100 47L99 47L99 46L98 46L98 44L97 44L97 43L95 43L95 45L94 45L94 44L93 44L93 43L94 43L94 41L95 41L95 40L98 40L98 39L97 39L97 36L98 36L98 35L99 35L99 36L100 36L100 38L101 38L101 36L100 36L100 35L99 35L99 34L98 34L98 32L97 32L97 31L96 31L96 29L95 29L95 28L93 28L93 27L94 27L94 24L95 24L95 25L96 25L96 24L97 24L97 25L98 25L98 23L94 23L94 22L97 22L97 21L96 21L96 20L98 20L98 19L96 19L96 18L95 18L95 20L94 20L94 18L93 18L93 19L92 19L92 17L90 17L90 15L89 15L89 14L86 14L86 15L83 15L83 16L84 16L84 17L85 17L85 16L86 16L86 19L85 19L85 18L84 18L84 19L82 19L82 21L81 21L81 23L80 23L80 24L79 24L79 26L78 26L78 25L76 25L76 26L74 26L74 25L73 25L73 24L76 24L76 23L75 23L75 21L74 21L74 19L75 19L75 20L76 20L76 19L78 19L78 20L77 20L77 21L80 21L80 20L81 20L81 18L78 18L78 16L77 16L77 17L76 17L76 19L75 19L75 18L74 18L74 19L70 19L70 18L67 18L67 17L66 17L66 18L65 18L65 17L63 17L63 16L64 16L64 15L65 15L65 16L66 16L66 15L67 15L67 14L66 14L66 15L65 15L65 14L64 14L64 15L63 15L63 16L62 16L62 15L61 15L61 14L60 14L60 13ZM81 13L81 14L82 14L82 13ZM100 13L100 14L102 14L102 13ZM31 14L31 16L32 16L32 15L33 15L33 16L34 16L34 15L33 15L33 14ZM45 14L45 15L44 15L44 16L46 16L46 14ZM88 15L88 16L87 16L87 19L86 19L86 20L87 20L87 21L85 21L85 20L83 20L83 21L85 21L85 22L84 22L84 23L82 23L82 24L81 24L81 25L80 25L80 26L79 26L79 27L75 27L75 28L74 28L74 30L73 30L73 29L72 29L72 28L71 28L71 30L70 30L70 28L69 28L69 30L70 30L70 31L69 31L69 32L71 32L71 30L72 30L72 31L74 31L74 30L76 30L76 32L72 32L72 33L74 33L74 34L75 34L75 33L77 33L77 34L76 34L76 36L75 36L75 35L73 35L73 37L72 37L72 36L71 36L71 37L70 37L70 36L69 36L69 37L67 37L67 36L68 36L68 35L69 35L69 34L70 34L70 35L72 35L72 34L70 34L70 33L68 33L68 31L67 31L67 33L68 33L68 35L67 35L67 34L66 34L66 35L65 35L65 34L64 34L64 33L66 33L66 31L64 31L64 33L63 33L63 34L64 34L64 37L65 37L65 38L66 38L66 37L67 37L67 39L66 39L66 40L65 40L65 39L64 39L64 42L63 42L63 43L64 43L64 44L65 44L65 43L66 43L66 45L64 45L64 46L63 46L63 44L62 44L62 43L60 43L60 44L59 44L59 46L58 46L58 48L57 48L57 47L56 47L56 48L55 48L55 47L54 47L54 49L53 49L53 47L50 47L50 45L49 45L49 46L48 46L48 47L49 47L49 48L47 48L47 46L46 46L46 47L45 47L45 44L44 44L44 42L45 42L45 43L48 43L48 41L50 41L50 42L49 42L49 43L50 43L50 42L51 42L51 41L52 41L52 40L51 40L51 39L50 39L50 37L48 37L48 36L47 36L47 35L48 35L48 34L46 34L46 35L45 35L45 36L47 36L47 38L48 38L48 39L50 39L50 40L47 40L47 42L46 42L46 41L45 41L45 40L42 40L42 42L43 42L43 44L44 44L44 45L43 45L43 46L42 46L42 47L41 47L41 46L40 46L40 50L39 50L39 51L40 51L40 52L41 52L41 55L40 55L40 56L41 56L41 55L42 55L42 57L40 57L40 58L39 58L39 59L40 59L40 60L41 60L41 59L42 59L42 61L41 61L41 62L42 62L42 61L43 61L43 62L45 62L45 61L44 61L44 59L42 59L42 58L46 58L46 59L45 59L45 60L46 60L46 63L45 63L45 64L44 64L44 63L43 63L43 64L42 64L42 63L40 63L40 62L39 62L39 63L40 63L40 64L42 64L42 65L40 65L40 68L42 68L42 67L41 67L41 66L42 66L42 65L43 65L43 69L41 69L41 71L40 71L40 72L43 72L43 74L44 74L44 71L45 71L45 75L48 75L48 73L46 73L46 72L47 72L47 67L48 67L48 66L49 66L49 67L50 67L50 66L51 66L51 67L54 67L54 68L52 68L52 69L51 69L51 71L52 71L52 72L50 72L50 73L49 73L49 75L50 75L50 74L51 74L51 73L52 73L52 74L53 74L53 73L54 73L54 74L55 74L55 72L54 72L54 71L56 71L56 73L58 73L58 74L57 74L57 75L56 75L56 76L57 76L57 79L58 79L58 80L59 80L59 79L58 79L58 76L59 76L59 77L60 77L60 79L62 79L62 78L63 78L63 77L64 77L64 76L65 76L65 78L66 78L66 75L65 75L65 73L67 73L67 76L68 76L68 77L67 77L67 79L69 79L69 78L70 78L70 79L71 79L71 77L69 77L69 76L71 76L71 75L73 75L73 76L72 76L72 78L73 78L73 80L72 80L72 81L74 81L74 82L75 82L75 83L73 83L73 82L71 82L71 81L70 81L70 83L73 83L73 84L75 84L75 83L76 83L76 84L78 84L78 85L76 85L76 86L75 86L75 87L74 87L74 86L73 86L73 88L72 88L72 89L70 89L70 88L71 88L71 85L69 85L69 86L68 86L68 82L69 82L69 81L67 81L67 80L66 80L66 79L64 79L64 81L63 81L63 80L62 80L62 81L63 81L63 83L64 83L64 84L65 84L65 85L64 85L64 86L63 86L63 87L64 87L64 86L65 86L65 85L66 85L66 86L67 86L67 88L66 88L66 89L67 89L67 90L70 90L70 91L69 91L69 92L68 92L68 93L69 93L69 94L68 94L68 95L69 95L69 94L70 94L70 95L74 95L74 93L75 93L75 94L76 94L76 96L75 96L75 98L76 98L76 101L74 101L74 100L75 100L75 99L74 99L74 96L72 96L72 97L73 97L73 99L74 99L74 100L70 100L70 99L71 99L71 98L70 98L70 99L69 99L69 100L68 100L68 97L69 97L69 96L68 96L68 97L67 97L67 100L65 100L65 98L66 98L66 97L65 97L65 98L64 98L64 100L62 100L62 102L63 102L63 103L65 103L65 101L68 101L68 102L69 102L69 101L71 101L71 102L70 102L70 103L73 103L73 104L74 104L74 102L75 102L75 104L76 104L76 105L77 105L77 104L80 104L80 103L81 103L81 104L82 104L82 105L83 105L83 104L82 104L82 103L83 103L83 100L86 100L86 99L87 99L87 100L90 100L90 99L88 99L88 97L89 97L89 94L88 94L88 92L87 92L87 91L88 91L88 89L89 89L89 88L92 88L92 89L90 89L90 90L92 90L92 91L91 91L91 92L92 92L92 91L95 91L95 89L94 89L94 88L95 88L95 87L96 87L96 86L94 86L94 84L92 84L92 83L93 83L93 82L94 82L94 83L96 83L96 80L95 80L95 81L94 81L94 80L93 80L93 81L92 81L92 79L91 79L91 81L90 81L90 82L91 82L91 85L89 85L89 86L91 86L91 85L92 85L92 86L94 86L94 87L93 87L93 88L92 88L92 87L88 87L88 89L87 89L87 88L86 88L86 87L87 87L87 86L88 86L88 85L87 85L87 83L88 83L88 84L90 84L90 83L88 83L88 81L89 81L89 79L88 79L88 78L87 78L87 79L86 79L86 78L85 78L85 76L86 76L86 77L87 77L87 76L86 76L86 75L88 75L88 76L89 76L89 77L91 77L91 78L94 78L94 77L93 77L93 76L96 76L96 75L97 75L97 76L98 76L98 73L99 73L99 74L100 74L100 73L99 73L99 71L100 71L100 70L102 70L102 69L100 69L100 70L99 70L99 68L96 68L96 69L95 69L95 68L94 68L94 71L93 71L93 72L94 72L94 73L97 73L97 74L96 74L96 75L95 75L95 74L94 74L94 75L93 75L93 76L92 76L92 74L91 74L91 76L89 76L89 75L88 75L88 74L90 74L90 73L92 73L92 71L90 71L90 72L89 72L89 70L91 70L91 68L92 68L92 67L95 67L95 65L98 65L98 66L96 66L96 67L98 67L98 66L100 66L100 68L101 68L101 66L102 66L102 65L101 65L101 64L102 64L102 63L101 63L101 62L103 62L103 61L102 61L102 60L101 60L101 59L100 59L100 60L101 60L101 61L100 61L100 62L99 62L99 60L98 60L98 57L99 57L99 56L98 56L98 57L95 57L95 56L96 56L96 55L95 55L95 50L96 50L96 51L98 51L98 48L97 48L97 50L96 50L96 48L95 48L95 47L98 47L98 46L94 46L94 49L93 49L93 48L92 48L92 47L93 47L93 46L91 46L91 45L92 45L92 44L90 44L90 43L88 43L88 42L91 42L91 43L93 43L93 41L94 41L94 40L95 40L95 39L96 39L96 38L94 38L94 36L95 36L95 35L96 35L96 34L97 34L97 35L98 35L98 34L97 34L97 32L94 32L94 35L93 35L93 38L92 38L92 34L93 34L93 32L92 32L92 31L91 31L91 32L92 32L92 34L91 34L91 36L90 36L90 35L89 35L89 33L88 33L88 32L89 32L89 31L88 31L88 30L90 30L90 29L91 29L91 28L92 28L92 25L93 25L93 24L94 24L94 23L93 23L93 24L92 24L92 23L89 23L89 22L88 22L88 21L91 21L91 22L94 22L94 20L93 20L93 21L91 21L91 20L92 20L92 19L91 19L91 20L90 20L90 19L88 19L88 16L89 16L89 15ZM11 16L11 17L9 17L9 18L11 18L11 19L12 19L12 18L11 18L11 17L13 17L13 16ZM17 16L17 17L19 17L19 16ZM35 16L35 18L34 18L34 17L33 17L33 18L31 18L31 19L34 19L34 21L32 21L32 20L29 20L29 19L30 19L30 18L29 18L29 19L28 19L28 18L27 18L27 19L28 19L28 20L29 20L29 23L30 23L30 22L31 22L31 21L32 21L32 22L33 22L33 23L35 23L35 22L34 22L34 21L36 21L36 20L35 20L35 18L37 18L37 17L36 17L36 16ZM45 17L45 19L44 19L44 21L46 21L46 22L47 22L47 21L46 21L46 20L45 20L45 19L46 19L46 17ZM48 17L48 18L49 18L49 17ZM111 17L111 18L112 18L112 17ZM17 18L17 19L18 19L18 21L19 21L19 18ZM63 18L63 19L64 19L64 20L63 20L63 21L64 21L64 22L67 22L67 21L64 21L64 20L65 20L65 18ZM8 19L8 20L10 20L10 19ZM66 19L66 20L68 20L68 21L69 21L69 22L70 22L70 24L71 24L71 23L72 23L72 24L73 24L73 23L74 23L74 21L71 21L71 22L70 22L70 20L68 20L68 19ZM79 19L79 20L80 20L80 19ZM87 19L87 20L88 20L88 19ZM106 19L106 20L105 20L105 21L103 21L103 22L104 22L104 24L103 24L103 25L104 25L104 24L105 24L105 23L106 23L106 20L108 20L108 19ZM61 20L61 21L60 21L60 22L57 22L57 23L56 23L56 27L60 27L60 28L61 28L61 27L62 27L62 28L63 28L63 27L64 27L64 24L65 24L65 27L66 27L66 25L67 25L67 24L68 24L68 23L67 23L67 24L65 24L65 23L64 23L64 24L63 24L63 23L62 23L62 20ZM6 22L6 23L5 23L5 24L6 24L6 25L7 25L7 24L9 24L9 23L8 23L8 22ZM22 22L22 23L23 23L23 24L24 24L24 23L23 23L23 22ZM60 22L60 23L57 23L57 26L58 26L58 24L61 24L61 22ZM72 22L72 23L73 23L73 22ZM77 22L77 23L79 23L79 22ZM86 22L86 23L84 23L84 24L82 24L82 25L81 25L81 26L82 26L82 25L84 25L84 24L85 24L85 26L86 26L86 27L89 27L89 29L90 29L90 27L91 27L91 26L90 26L90 25L91 25L91 24L88 24L88 23L87 23L87 22ZM6 23L6 24L7 24L7 23ZM31 23L31 24L32 24L32 23ZM19 24L19 25L20 25L20 24ZM86 24L86 26L88 26L88 24ZM13 25L13 26L14 26L14 25ZM61 25L61 26L60 26L60 27L61 27L61 26L62 26L62 27L63 27L63 26L62 26L62 25ZM71 25L71 26L72 26L72 27L74 27L74 26L72 26L72 25ZM29 26L29 27L30 27L30 28L31 28L31 26ZM83 26L83 27L84 27L84 29L83 29L83 28L82 28L82 29L81 29L81 31L79 31L79 28L78 28L78 29L77 29L77 32L78 32L78 33L80 33L80 34L82 34L82 32L81 32L81 31L82 31L82 30L84 30L84 29L85 29L85 27L84 27L84 26ZM89 26L89 27L90 27L90 26ZM95 26L95 27L96 27L96 28L98 28L98 27L96 27L96 26ZM101 26L101 27L103 27L103 26ZM1 27L1 28L2 28L2 29L1 29L1 30L2 30L2 29L3 29L3 28L5 28L5 29L6 29L6 30L7 30L7 29L6 29L6 28L7 28L7 27L6 27L6 28L5 28L5 27ZM18 27L18 28L19 28L19 27ZM40 27L40 29L41 29L41 30L43 30L43 29L42 29L42 28L41 28L41 27ZM80 27L80 28L81 28L81 27ZM75 28L75 29L76 29L76 28ZM87 28L87 29L86 29L86 30L88 30L88 28ZM101 28L101 29L102 29L102 28ZM31 29L31 30L32 30L32 29ZM94 29L94 30L93 30L93 31L95 31L95 29ZM98 29L98 31L99 31L99 29ZM20 30L20 31L21 31L21 30ZM45 30L45 31L44 31L44 32L45 32L45 31L46 31L46 30ZM106 30L106 31L105 31L105 32L106 32L106 31L107 31L107 30ZM5 31L5 34L8 34L8 31ZM24 31L24 32L23 32L23 33L22 33L22 34L20 34L20 35L21 35L21 36L23 36L23 35L24 35L24 32L25 32L25 31ZM28 31L28 32L29 32L29 31ZM31 31L31 34L34 34L34 31ZM35 31L35 33L36 33L36 32L37 32L37 33L38 33L38 31ZM57 31L57 34L60 34L60 31ZM78 31L78 32L79 32L79 31ZM83 31L83 34L86 34L86 31ZM87 31L87 32L88 32L88 31ZM109 31L109 34L112 34L112 31ZM6 32L6 33L7 33L7 32ZM32 32L32 33L33 33L33 32ZM58 32L58 33L59 33L59 32ZM84 32L84 33L85 33L85 32ZM110 32L110 33L111 33L111 32ZM0 33L0 34L1 34L1 35L0 35L0 36L2 36L2 34L1 34L1 33ZM95 33L95 34L96 34L96 33ZM22 34L22 35L23 35L23 34ZM77 34L77 36L76 36L76 37L77 37L77 36L78 36L78 35L79 35L79 38L76 38L76 39L75 39L75 38L74 38L74 37L73 37L73 38L74 38L74 40L73 40L73 39L72 39L72 38L71 38L71 39L72 39L72 40L70 40L70 37L69 37L69 38L68 38L68 40L66 40L66 41L68 41L68 40L70 40L70 41L69 41L69 43L67 43L67 42L66 42L66 43L67 43L67 44L69 44L69 45L67 45L67 46L66 46L66 47L65 47L65 46L64 46L64 47L63 47L63 46L62 46L62 47L61 47L61 46L59 46L59 48L58 48L58 49L57 49L57 48L56 48L56 49L57 49L57 50L56 50L56 51L55 51L55 50L53 50L53 49L52 49L52 50L50 50L50 51L48 51L48 50L47 50L47 48L46 48L46 50L45 50L45 47L44 47L44 46L43 46L43 47L42 47L42 49L41 49L41 51L42 51L42 52L43 52L43 51L45 51L45 52L44 52L44 53L43 53L43 54L42 54L42 55L44 55L44 56L43 56L43 57L44 57L44 56L45 56L45 57L47 57L47 59L46 59L46 60L47 60L47 59L48 59L48 60L49 60L49 62L50 62L50 63L48 63L48 65L49 65L49 66L50 66L50 65L51 65L51 66L52 66L52 65L53 65L53 66L54 66L54 67L55 67L55 68L54 68L54 69L53 69L53 70L52 70L52 71L54 71L54 70L55 70L55 68L56 68L56 71L57 71L57 66L59 66L59 64L58 64L58 65L55 65L55 62L56 62L56 63L57 63L57 62L58 62L58 63L61 63L61 62L59 62L59 61L57 61L57 62L56 62L56 61L55 61L55 62L53 62L53 63L54 63L54 64L52 64L52 61L53 61L53 60L54 60L54 59L55 59L55 58L56 58L56 57L55 57L55 56L56 56L56 55L57 55L57 56L58 56L58 55L59 55L59 56L60 56L60 55L61 55L61 56L63 56L63 55L64 55L64 52L63 52L63 54L62 54L62 55L61 55L61 54L59 54L59 52L62 52L62 51L66 51L66 52L67 52L67 51L68 51L68 53L69 53L69 54L68 54L68 56L67 56L67 53L66 53L66 54L65 54L65 55L66 55L66 56L64 56L64 57L63 57L63 58L62 58L62 60L61 60L61 61L63 61L63 62L62 62L62 63L63 63L63 65L62 65L62 64L61 64L61 66L62 66L62 69L63 69L63 70L62 70L62 72L63 72L63 71L65 71L65 72L64 72L64 73L61 73L61 74L58 74L58 75L59 75L59 76L60 76L60 77L62 77L62 75L63 75L63 76L64 76L64 75L63 75L63 74L64 74L64 73L65 73L65 72L66 72L66 71L67 71L67 73L68 73L68 72L71 72L71 71L67 71L67 70L66 70L66 71L65 71L65 70L64 70L64 69L65 69L65 68L64 68L64 67L66 67L66 68L67 68L67 67L68 67L68 70L69 70L69 67L70 67L70 66L71 66L71 67L72 67L72 68L70 68L70 69L72 69L72 73L71 73L71 74L70 74L70 73L69 73L69 74L68 74L68 76L69 76L69 75L71 75L71 74L74 74L74 75L75 75L75 76L73 76L73 78L74 78L74 79L75 79L75 80L74 80L74 81L75 81L75 80L76 80L76 79L77 79L77 81L76 81L76 83L79 83L79 79L80 79L80 80L81 80L81 79L82 79L82 81L80 81L80 84L79 84L79 85L80 85L80 86L77 86L77 87L78 87L78 88L76 88L76 87L75 87L75 90L74 90L74 89L73 89L73 91L70 91L70 92L69 92L69 93L71 93L71 94L72 94L72 93L73 93L73 91L75 91L75 93L76 93L76 94L77 94L77 97L76 97L76 98L77 98L77 100L78 100L78 102L77 102L77 101L76 101L76 103L80 103L80 102L79 102L79 101L80 101L80 100L81 100L81 103L82 103L82 100L81 100L81 99L80 99L80 97L79 97L79 96L81 96L81 95L82 95L82 96L83 96L83 97L81 97L81 98L83 98L83 99L85 99L85 98L83 98L83 97L86 97L86 96L87 96L87 97L88 97L88 94L87 94L87 95L86 95L86 91L85 91L85 90L86 90L86 88L85 88L85 87L84 87L84 88L85 88L85 89L83 89L83 91L84 91L84 92L83 92L83 94L82 94L82 92L81 92L81 91L82 91L82 90L81 90L81 89L82 89L82 88L83 88L83 87L82 87L82 88L81 88L81 87L80 87L80 86L81 86L81 85L80 85L80 84L82 84L82 82L84 82L84 81L85 81L85 82L86 82L86 80L85 80L85 79L84 79L84 78L83 78L83 77L84 77L84 76L83 76L83 73L85 73L85 74L84 74L84 75L86 75L86 72L87 72L87 74L88 74L88 71L87 71L87 70L86 70L86 68L87 68L87 69L88 69L88 70L89 70L89 69L90 69L90 68L89 68L89 69L88 69L88 67L87 67L87 66L89 66L89 67L92 67L92 66L94 66L94 65L95 65L95 64L97 64L97 63L99 63L99 65L100 65L100 66L101 66L101 65L100 65L100 63L99 63L99 62L98 62L98 61L97 61L97 59L96 59L96 58L95 58L95 57L94 57L94 54L92 54L92 53L93 53L93 52L94 52L94 50L95 50L95 49L94 49L94 50L90 50L90 49L88 49L88 51L87 51L87 48L88 48L88 47L89 47L89 44L88 44L88 43L87 43L87 42L88 42L88 41L89 41L89 40L88 40L88 39L85 39L85 41L84 41L84 42L83 42L83 43L80 43L80 44L79 44L79 42L81 42L81 41L83 41L83 40L84 40L84 38L85 38L85 37L84 37L84 38L82 38L82 37L83 37L83 35L82 35L82 36L81 36L81 35L79 35L79 34ZM13 35L13 36L14 36L14 38L15 38L15 36L14 36L14 35ZM16 35L16 36L17 36L17 37L18 37L18 36L17 36L17 35ZM26 35L26 36L27 36L27 35ZM36 35L36 36L37 36L37 35ZM38 35L38 37L37 37L37 38L36 38L36 37L35 37L35 38L34 38L34 40L32 40L32 41L31 41L31 39L33 39L33 38L32 38L32 37L29 37L29 39L27 39L27 41L28 41L28 42L26 42L26 41L25 41L25 43L28 43L28 45L27 45L27 46L28 46L28 48L27 48L27 47L26 47L26 45L25 45L25 48L24 48L24 53L23 53L23 50L22 50L22 49L21 49L21 51L20 51L20 52L21 52L21 53L20 53L20 54L21 54L21 53L22 53L22 54L24 54L24 56L25 56L25 55L26 55L26 54L27 54L27 53L29 53L29 51L30 51L30 50L29 50L29 47L31 47L31 45L30 45L30 44L29 44L29 43L32 43L32 42L34 42L34 43L33 43L33 44L32 44L32 47L33 47L33 46L34 46L34 45L36 45L36 46L37 46L37 47L38 47L38 46L39 46L39 45L40 45L40 44L41 44L41 45L42 45L42 44L41 44L41 42L40 42L40 40L38 40L38 38L39 38L39 37L40 37L40 36L41 36L41 35L40 35L40 36L39 36L39 35ZM54 35L54 36L55 36L55 37L56 37L56 36L55 36L55 35ZM66 35L66 36L67 36L67 35ZM86 35L86 36L88 36L88 37L89 37L89 39L92 39L92 40L94 40L94 38L93 38L93 39L92 39L92 38L91 38L91 37L90 37L90 36L88 36L88 35ZM110 36L110 37L111 37L111 36ZM19 38L19 39L20 39L20 38ZM30 38L30 39L29 39L29 40L28 40L28 41L30 41L30 39L31 39L31 38ZM35 38L35 39L36 39L36 40L37 40L37 41L38 41L38 43L35 43L35 44L38 44L38 43L40 43L40 42L39 42L39 41L38 41L38 40L37 40L37 39L36 39L36 38ZM56 38L56 39L57 39L57 38ZM79 38L79 39L76 39L76 40L74 40L74 41L76 41L76 42L75 42L75 43L73 43L73 44L70 44L70 45L69 45L69 46L70 46L70 47L69 47L69 50L70 50L70 51L73 51L73 53L75 53L75 51L76 51L76 54L75 54L75 56L74 56L74 57L73 57L73 59L74 59L74 58L77 58L77 57L78 57L78 58L80 58L80 59L79 59L79 61L76 61L76 62L75 62L75 60L71 60L71 58L72 58L72 57L71 57L71 58L70 58L70 59L69 59L69 56L72 56L72 55L74 55L74 54L72 54L72 52L71 52L71 54L70 54L70 55L69 55L69 56L68 56L68 59L66 59L66 60L65 60L65 59L64 59L64 61L65 61L65 62L67 62L67 60L68 60L68 61L69 61L69 62L70 62L70 63L64 63L64 62L63 62L63 63L64 63L64 64L66 64L66 65L64 65L64 66L66 66L66 65L68 65L68 64L71 64L71 63L73 63L73 65L72 65L72 67L73 67L73 68L75 68L75 69L74 69L74 70L73 70L73 71L74 71L74 72L75 72L75 71L76 71L76 73L74 73L74 74L75 74L75 75L76 75L76 76L75 76L75 77L74 77L74 78L75 78L75 79L76 79L76 78L75 78L75 77L76 77L76 76L77 76L77 78L81 78L81 77L82 77L82 75L81 75L81 76L80 76L80 74L82 74L82 73L83 73L83 71L82 71L82 69L83 69L83 70L85 70L85 72L86 72L86 70L85 70L85 69L83 69L83 68L84 68L84 67L85 67L85 68L86 68L86 67L85 67L85 64L86 64L86 66L87 66L87 65L89 65L89 66L92 66L92 65L93 65L93 64L95 64L95 63L97 63L97 62L95 62L95 61L94 61L94 63L93 63L93 61L90 61L90 62L89 62L89 63L87 63L87 60L92 60L92 59L93 59L93 60L95 60L95 58L94 58L94 57L91 57L91 56L92 56L92 55L91 55L91 56L90 56L90 55L87 55L87 56L86 56L86 55L85 55L85 54L91 54L91 53L92 53L92 51L88 51L88 52L86 52L86 51L85 51L85 50L86 50L86 49L85 49L85 48L86 48L86 46L84 46L84 45L85 45L85 44L86 44L86 45L87 45L87 47L88 47L88 44L86 44L86 42L87 42L87 41L86 41L86 42L84 42L84 43L85 43L85 44L83 44L83 45L82 45L82 46L81 46L81 44L80 44L80 47L79 47L79 48L78 48L78 47L76 47L76 49L77 49L77 51L76 51L76 50L75 50L75 49L74 49L74 50L73 50L73 49L72 49L72 48L71 48L71 49L70 49L70 47L74 47L74 46L73 46L73 44L75 44L75 43L76 43L76 44L77 44L77 42L79 42L79 41L81 41L81 40L82 40L82 38ZM111 38L111 39L112 39L112 41L113 41L113 42L114 42L114 41L115 41L115 40L114 40L114 41L113 41L113 39L112 39L112 38ZM4 39L4 40L5 40L5 39ZM7 39L7 40L6 40L6 41L9 41L9 39ZM80 39L80 40L81 40L81 39ZM2 40L2 41L3 41L3 40ZM11 40L11 41L12 41L12 40ZM34 40L34 41L35 41L35 40ZM72 40L72 41L73 41L73 40ZM78 40L78 41L79 41L79 40ZM17 41L17 42L18 42L18 41ZM19 41L19 42L20 42L20 41ZM43 41L43 42L44 42L44 41ZM91 41L91 42L92 42L92 41ZM99 41L99 42L101 42L101 41ZM104 41L104 42L105 42L105 41ZM11 42L11 44L10 44L10 46L9 46L9 47L11 47L11 48L12 48L12 49L13 49L13 50L14 50L14 49L13 49L13 48L14 48L14 47L12 47L12 46L14 46L14 44L13 44L13 45L12 45L12 42ZM28 42L28 43L29 43L29 42ZM70 42L70 43L72 43L72 42ZM8 44L8 45L9 45L9 44ZM17 44L17 45L16 45L16 46L17 46L17 45L18 45L18 44ZM47 44L47 45L48 45L48 44ZM61 44L61 45L62 45L62 44ZM78 44L78 45L76 45L76 46L78 46L78 45L79 45L79 44ZM96 44L96 45L97 45L97 44ZM99 44L99 45L100 45L100 46L101 46L101 45L100 45L100 44ZM28 45L28 46L30 46L30 45ZM37 45L37 46L38 46L38 45ZM53 45L53 46L54 46L54 45ZM70 45L70 46L72 46L72 45ZM6 46L6 47L7 47L7 46ZM67 46L67 47L66 47L66 48L68 48L68 46ZM82 46L82 47L80 47L80 48L79 48L79 49L78 49L78 51L77 51L77 54L76 54L76 55L78 55L78 56L79 56L79 57L80 57L80 58L81 58L81 59L80 59L80 60L82 60L82 58L81 58L81 56L82 56L82 55L80 55L80 54L84 54L84 50L83 50L83 49L82 49L82 48L83 48L83 47L84 47L84 48L85 48L85 47L84 47L84 46ZM90 46L90 47L91 47L91 46ZM113 46L113 48L115 48L115 49L116 49L116 47L115 47L115 46ZM0 47L0 48L1 48L1 49L2 49L2 47ZM34 47L34 48L35 48L35 47ZM43 47L43 50L44 50L44 47ZM60 47L60 48L59 48L59 49L58 49L58 52L57 52L57 51L56 51L56 52L55 52L55 51L50 51L50 52L48 52L48 51L47 51L47 50L46 50L46 51L47 51L47 53L46 53L46 54L44 54L44 55L47 55L47 56L48 56L48 55L49 55L49 56L51 56L51 57L53 57L53 55L50 55L50 54L52 54L52 53L53 53L53 52L55 52L55 55L54 55L54 56L55 56L55 55L56 55L56 53L58 53L58 52L59 52L59 51L62 51L62 50L63 50L63 49L62 49L62 48L63 48L63 47L62 47L62 48L61 48L61 47ZM64 47L64 48L65 48L65 47ZM18 48L18 49L16 49L16 50L18 50L18 51L17 51L17 52L19 52L19 50L20 50L20 49L19 49L19 48ZM25 48L25 49L26 49L26 51L25 51L25 53L24 53L24 54L25 54L25 53L27 53L27 52L28 52L28 51L29 51L29 50L27 50L27 49L26 49L26 48ZM32 48L32 49L31 49L31 50L32 50L32 49L33 49L33 48ZM49 48L49 49L51 49L51 48ZM10 49L10 50L11 50L11 51L12 51L12 50L11 50L11 49ZM59 49L59 50L60 50L60 49ZM61 49L61 50L62 50L62 49ZM64 49L64 50L65 50L65 49ZM66 49L66 50L68 50L68 49ZM71 49L71 50L72 50L72 49ZM79 49L79 50L81 50L81 51L82 51L82 52L80 52L80 51L78 51L78 53L79 53L79 54L80 54L80 53L83 53L83 51L82 51L82 50L81 50L81 49ZM6 50L6 51L7 51L7 50ZM74 50L74 51L75 51L75 50ZM21 51L21 52L22 52L22 51ZM35 51L35 52L37 52L37 51ZM112 51L112 53L111 53L111 52L110 52L110 54L112 54L112 53L113 53L113 51ZM4 52L4 53L5 53L5 52ZM6 52L6 53L7 53L7 52ZM69 52L69 53L70 53L70 52ZM88 52L88 53L89 53L89 52ZM90 52L90 53L91 53L91 52ZM17 53L17 54L18 54L18 55L19 55L19 53ZM47 53L47 55L48 55L48 53ZM49 53L49 54L50 54L50 53ZM31 54L31 55L33 55L33 54ZM57 54L57 55L58 55L58 54ZM71 54L71 55L72 55L72 54ZM113 54L113 55L114 55L114 54ZM5 55L5 56L7 56L7 55ZM37 55L37 56L38 56L38 57L39 57L39 56L38 56L38 55ZM79 55L79 56L80 56L80 55ZM84 55L84 56L85 56L85 55ZM66 56L66 57L65 57L65 58L66 58L66 57L67 57L67 56ZM76 56L76 57L77 57L77 56ZM88 56L88 57L87 57L87 58L88 58L88 59L91 59L91 58L88 58L88 57L89 57L89 56ZM5 57L5 60L8 60L8 57ZM31 57L31 60L34 60L34 57ZM49 57L49 59L50 59L50 60L51 60L51 59L52 59L52 60L53 60L53 58L51 58L51 59L50 59L50 57ZM54 57L54 58L55 58L55 57ZM57 57L57 60L60 60L60 57ZM83 57L83 60L86 60L86 57ZM109 57L109 60L112 60L112 57ZM6 58L6 59L7 59L7 58ZM32 58L32 59L33 59L33 58ZM58 58L58 59L59 59L59 58ZM84 58L84 59L85 59L85 58ZM93 58L93 59L94 59L94 58ZM110 58L110 59L111 59L111 58ZM0 59L0 65L2 65L2 64L1 64L1 63L2 63L2 62L1 62L1 61L2 61L2 60L1 60L1 59ZM68 59L68 60L69 60L69 61L71 61L71 60L69 60L69 59ZM76 59L76 60L77 60L77 59ZM17 60L17 61L16 61L16 62L17 62L17 61L19 61L19 60ZM25 61L25 63L26 63L26 62L27 62L27 61ZM31 61L31 62L32 62L32 61ZM47 61L47 62L48 62L48 61ZM73 61L73 62L74 62L74 64L76 64L76 66L77 66L77 67L79 67L79 66L80 66L80 65L82 65L82 67L84 67L84 66L83 66L83 63L84 63L84 62L83 62L83 61L82 61L82 62L81 62L81 61L80 61L80 62L81 62L81 63L79 63L79 62L77 62L77 64L76 64L76 63L75 63L75 62L74 62L74 61ZM85 61L85 63L86 63L86 64L87 64L87 63L86 63L86 61ZM111 61L111 62L112 62L112 61ZM14 62L14 63L15 63L15 62ZM34 62L34 63L35 63L35 62ZM90 62L90 63L89 63L89 65L92 65L92 64L93 64L93 63L91 63L91 62ZM46 63L46 65L47 65L47 63ZM78 63L78 64L79 64L79 63ZM81 63L81 64L82 64L82 63ZM90 63L90 64L91 64L91 63ZM25 64L25 65L26 65L26 64ZM28 64L28 66L29 66L29 68L30 68L30 69L31 69L31 67L32 67L32 65L31 65L31 67L30 67L30 66L29 66L29 64ZM43 64L43 65L44 65L44 69L43 69L43 70L42 70L42 71L43 71L43 70L44 70L44 69L45 69L45 71L46 71L46 68L45 68L45 67L46 67L46 66L45 66L45 65L44 65L44 64ZM108 64L108 65L109 65L109 64ZM38 65L38 66L37 66L37 67L38 67L38 66L39 66L39 65ZM69 65L69 66L68 66L68 67L69 67L69 66L70 66L70 65ZM73 65L73 67L75 67L75 68L76 68L76 69L75 69L75 70L74 70L74 71L75 71L75 70L76 70L76 69L77 69L77 68L76 68L76 67L75 67L75 66L74 66L74 65ZM59 67L59 68L58 68L58 70L59 70L59 69L61 69L61 68L60 68L60 67ZM80 67L80 68L81 68L81 69L82 69L82 68L81 68L81 67ZM102 67L102 68L103 68L103 70L104 70L104 71L107 71L107 70L106 70L106 69L105 69L105 70L104 70L104 67ZM6 68L6 69L7 69L7 68ZM9 68L9 69L10 69L10 68ZM12 68L12 69L13 69L13 71L14 71L14 70L15 70L15 71L16 71L16 70L15 70L15 69L13 69L13 68ZM33 68L33 69L34 69L34 68ZM36 68L36 69L35 69L35 70L34 70L34 71L33 71L33 73L32 73L32 74L33 74L33 73L34 73L34 72L35 72L35 74L36 74L36 72L35 72L35 71L36 71L36 70L37 70L37 75L38 75L38 76L36 76L36 77L37 77L37 80L36 80L36 81L35 81L35 82L34 82L34 79L35 79L35 78L34 78L34 79L33 79L33 77L35 77L35 76L34 76L34 75L33 75L33 77L31 77L31 79L30 79L30 77L29 77L29 79L28 79L28 80L29 80L29 79L30 79L30 81L29 81L29 82L30 82L30 84L29 84L29 83L28 83L28 84L29 84L29 85L30 85L30 86L29 86L29 87L27 87L27 88L33 88L33 90L34 90L34 92L35 92L35 93L37 93L37 92L35 92L35 91L36 91L36 90L39 90L39 91L42 91L42 90L39 90L39 89L38 89L38 88L39 88L39 87L38 87L38 86L39 86L39 83L40 83L40 84L41 84L41 83L42 83L42 84L43 84L43 83L45 83L45 82L46 82L46 83L48 83L48 82L47 82L47 80L46 80L46 79L47 79L47 78L49 78L49 77L50 77L50 78L52 78L52 77L51 77L51 76L49 76L49 77L47 77L47 76L46 76L46 77L45 77L45 76L44 76L44 75L42 75L42 76L41 76L41 73L40 73L40 77L41 77L41 78L40 78L40 81L41 81L41 82L39 82L39 80L38 80L38 76L39 76L39 75L38 75L38 74L39 74L39 73L38 73L38 72L39 72L39 71L38 71L38 70L39 70L39 68ZM49 68L49 69L48 69L48 70L49 70L49 71L50 71L50 68ZM78 68L78 69L79 69L79 70L77 70L77 71L78 71L78 73L79 73L79 74L76 74L76 75L79 75L79 74L80 74L80 73L81 73L81 72L82 72L82 71L79 71L79 70L80 70L80 69L79 69L79 68ZM116 68L116 69L117 69L117 68ZM4 69L4 70L5 70L5 71L7 71L7 70L5 70L5 69ZM19 69L19 70L21 70L21 69ZM97 69L97 71L96 71L96 70L95 70L95 71L96 71L96 72L97 72L97 73L98 73L98 72L97 72L97 71L99 71L99 70L98 70L98 69ZM21 71L21 72L22 72L22 71ZM58 71L58 73L60 73L60 72L59 72L59 71ZM52 72L52 73L53 73L53 72ZM79 72L79 73L80 73L80 72ZM0 73L0 74L1 74L1 73ZM3 73L3 74L4 74L4 73ZM6 74L6 75L7 75L7 74ZM61 74L61 75L62 75L62 74ZM101 74L101 75L102 75L102 74ZM16 75L16 76L17 76L17 75ZM28 75L28 76L29 76L29 75ZM52 75L52 76L54 76L54 77L53 77L53 78L54 78L54 77L55 77L55 76L54 76L54 75ZM21 76L21 77L22 77L22 76ZM43 76L43 77L42 77L42 78L41 78L41 81L42 81L42 80L43 80L43 78L45 78L45 79L44 79L44 80L45 80L45 79L46 79L46 78L47 78L47 77L46 77L46 78L45 78L45 77L44 77L44 76ZM79 76L79 77L80 77L80 76ZM91 76L91 77L92 77L92 76ZM11 77L11 78L12 78L12 77ZM27 77L27 78L28 78L28 77ZM6 79L6 80L7 80L7 79ZM14 79L14 80L13 80L13 81L14 81L14 80L15 80L15 79ZM49 79L49 81L50 81L50 82L49 82L49 84L50 84L50 82L51 82L51 81L53 81L53 82L55 82L55 81L53 81L53 79L52 79L52 80L50 80L50 79ZM87 79L87 81L88 81L88 79ZM22 80L22 81L23 81L23 82L24 82L24 81L23 81L23 80ZM32 80L32 81L31 81L31 82L32 82L32 81L33 81L33 80ZM37 80L37 81L38 81L38 80ZM65 80L65 81L64 81L64 83L65 83L65 84L67 84L67 82L66 82L66 83L65 83L65 81L66 81L66 80ZM83 80L83 81L84 81L84 80ZM6 81L6 82L7 82L7 81ZM15 81L15 82L16 82L16 81ZM26 81L26 82L28 82L28 81ZM43 81L43 82L42 82L42 83L43 83L43 82L44 82L44 81ZM105 81L105 82L106 82L106 81ZM109 81L109 82L110 82L110 81ZM0 82L0 83L1 83L1 82ZM35 82L35 83L36 83L36 84L37 84L37 82ZM38 82L38 83L39 83L39 82ZM101 82L101 83L102 83L102 84L101 84L101 85L102 85L102 84L103 84L103 82ZM5 83L5 86L8 86L8 83ZM31 83L31 86L34 86L34 83ZM57 83L57 86L60 86L60 83ZM83 83L83 86L86 86L86 83ZM98 83L98 84L99 84L99 83ZM109 83L109 86L112 86L112 83ZM6 84L6 85L7 85L7 84ZM32 84L32 85L33 85L33 84ZM58 84L58 85L59 85L59 84ZM84 84L84 85L85 85L85 84ZM110 84L110 85L111 85L111 84ZM35 85L35 86L36 86L36 87L33 87L33 88L35 88L35 89L34 89L34 90L36 90L36 88L38 88L38 87L37 87L37 86L36 86L36 85ZM49 86L49 87L48 87L48 88L46 88L46 89L47 89L47 90L48 90L48 88L49 88L49 89L50 89L50 88L49 88L49 87L51 87L51 88L52 88L52 87L53 87L53 86L52 86L52 87L51 87L51 86ZM69 86L69 87L68 87L68 89L69 89L69 88L70 88L70 86ZM5 87L5 88L7 88L7 87ZM79 87L79 88L78 88L78 89L77 89L77 90L79 90L79 89L81 89L81 88L80 88L80 87ZM109 87L109 88L110 88L110 87ZM43 88L43 90L44 90L44 88ZM103 88L103 89L104 89L104 88ZM19 89L19 91L20 91L20 89ZM29 89L29 91L28 91L28 92L26 92L26 91L25 91L25 94L27 94L27 95L28 95L28 93L29 93L29 92L30 92L30 89ZM54 89L54 90L55 90L55 91L56 91L56 92L57 92L57 91L56 91L56 89ZM93 89L93 90L94 90L94 89ZM100 89L100 90L101 90L101 89ZM11 90L11 91L12 91L12 90ZM17 90L17 92L18 92L18 90ZM75 90L75 91L76 91L76 93L77 93L77 94L79 94L79 95L81 95L81 93L80 93L80 91L81 91L81 90L80 90L80 91L76 91L76 90ZM31 92L31 93L32 93L32 94L33 94L33 96L34 96L34 93L32 93L32 92ZM50 92L50 93L49 93L49 96L48 96L48 97L49 97L49 96L50 96L50 94L52 94L52 93L51 93L51 92ZM71 92L71 93L72 93L72 92ZM77 92L77 93L79 93L79 94L80 94L80 93L79 93L79 92ZM84 92L84 93L85 93L85 92ZM6 93L6 94L7 94L7 93ZM54 93L54 94L56 94L56 93ZM108 93L108 94L109 94L109 93ZM35 94L35 95L38 95L38 94ZM40 94L40 95L41 95L41 94ZM62 94L62 95L63 95L63 94ZM98 94L98 95L99 95L99 94ZM5 95L5 96L4 96L4 97L5 97L5 96L7 96L7 95ZM51 95L51 96L52 96L52 97L53 97L53 98L52 98L52 100L51 100L51 99L49 99L49 98L48 98L48 99L49 99L49 100L47 100L47 101L46 101L46 103L47 103L47 102L49 102L49 103L50 103L50 102L49 102L49 100L50 100L50 101L51 101L51 102L52 102L52 100L54 100L54 99L53 99L53 98L54 98L54 97L53 97L53 96L54 96L54 95ZM83 95L83 96L86 96L86 95ZM101 95L101 96L102 96L102 95ZM105 95L105 96L106 96L106 95ZM2 96L2 97L3 97L3 96ZM70 96L70 97L71 97L71 96ZM115 96L115 97L116 97L116 96ZM50 97L50 98L51 98L51 97ZM58 97L58 99L61 99L61 98L59 98L59 97ZM77 97L77 98L79 98L79 97ZM103 97L103 98L105 98L105 97ZM12 98L12 99L13 99L13 98ZM42 99L42 100L43 100L43 99ZM79 99L79 100L80 100L80 99ZM113 99L113 100L115 100L115 99ZM0 101L0 103L1 103L1 102L2 102L2 103L4 103L4 102L2 102L2 101ZM6 101L6 102L8 102L8 101ZM10 101L10 102L11 102L11 103L12 103L12 101ZM14 101L14 102L15 102L15 101ZM21 101L21 102L22 102L22 101ZM33 101L33 102L34 102L34 101ZM73 101L73 102L74 102L74 101ZM86 101L86 102L85 102L85 104L84 104L84 105L85 105L85 104L86 104L86 102L87 102L87 101ZM6 103L6 104L7 104L7 103ZM114 103L114 105L115 105L115 104L116 104L116 103ZM0 104L0 107L1 107L1 106L2 106L2 108L1 108L1 109L3 109L3 106L2 106L2 104ZM15 104L15 105L13 105L13 106L15 106L15 105L16 105L16 104ZM45 104L45 105L46 105L46 104ZM31 105L31 107L32 107L32 105ZM80 105L80 106L81 106L81 105ZM112 105L112 106L107 106L107 110L108 110L108 107L112 107L112 106L113 106L113 105ZM39 106L39 107L40 107L40 108L41 108L41 109L42 109L42 107L40 107L40 106ZM11 107L11 108L13 108L13 107ZM43 107L43 108L44 108L44 107ZM98 107L98 108L99 108L99 107ZM103 107L103 108L104 108L104 107ZM15 108L15 109L14 109L14 110L13 110L13 111L12 111L12 113L11 113L11 114L10 114L10 113L9 113L9 112L10 112L10 111L8 111L8 113L9 113L9 114L10 114L10 116L9 116L9 115L8 115L8 117L10 117L10 116L12 116L12 117L13 117L13 115L15 115L15 114L13 114L13 112L14 112L14 110L15 110L15 113L16 113L16 112L17 112L17 111L16 111L16 108ZM53 108L53 109L52 109L52 110L53 110L53 111L54 111L54 112L55 112L55 113L56 113L56 112L55 112L55 111L56 111L56 109L54 109L54 108ZM8 109L8 110L9 110L9 109ZM28 109L28 110L29 110L29 111L30 111L30 109ZM31 109L31 112L34 112L34 109ZM57 109L57 112L60 112L60 109ZM83 109L83 112L86 112L86 109ZM102 109L102 110L103 110L103 111L105 111L105 110L104 110L104 109ZM109 109L109 112L112 112L112 109ZM20 110L20 112L22 112L22 110ZM32 110L32 111L33 111L33 110ZM45 110L45 111L44 111L44 112L45 112L45 115L43 115L43 116L45 116L45 115L46 115L46 112L48 112L48 113L50 113L50 112L49 112L49 111L46 111L46 110ZM58 110L58 111L59 111L59 110ZM84 110L84 111L85 111L85 110ZM110 110L110 111L111 111L111 110ZM114 110L114 111L113 111L113 112L116 112L116 111L115 111L115 110ZM45 111L45 112L46 112L46 111ZM61 111L61 114L62 114L62 111ZM19 113L19 114L20 114L20 115L19 115L19 116L20 116L20 115L21 115L21 114L20 114L20 113ZM43 113L43 114L44 114L44 113ZM97 113L97 114L98 114L98 113ZM112 113L112 114L113 114L113 113ZM11 114L11 115L13 115L13 114ZM57 114L57 115L56 115L56 116L57 116L57 115L58 115L58 114ZM105 114L105 115L104 115L104 116L105 116L105 115L106 115L106 114ZM67 115L67 116L66 116L66 117L67 117L67 116L68 116L68 115ZM23 116L23 117L25 117L25 116ZM116 116L116 117L117 117L117 116ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM117 0L110 0L110 7L117 7ZM116 1L111 1L111 6L116 6ZM115 2L112 2L112 5L115 5ZM0 117L7 117L7 110L0 110ZM1 116L6 116L6 111L1 111ZM2 115L5 115L5 112L2 112Z\" fill=\"#000000\"/></g></g></svg>\n",
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"512\" height=\"512\" viewBox=\"0 0 512 512\"><rect x=\"0\" y=\"0\" width=\"512\" height=\"512\" fill=\"#fefefe\"/><g transform=\"scale(3.436)\"><g transform=\"translate(16.000,16.000)\"><path fill-rule=\"evenodd\" d=\"M8 0L8 4L10 4L10 7L9 7L9 5L8 5L8 7L9 7L9 8L10 8L10 7L11 7L11 6L12 6L12 7L13 7L13 8L11 8L11 10L10 10L10 9L7 9L7 8L4 8L4 10L3 10L3 9L2 9L2 8L0 8L0 10L1 10L1 9L2 9L2 10L3 10L3 13L2 13L2 16L0 16L0 17L2 17L2 18L1 18L1 19L2 19L2 20L3 20L3 18L4 18L4 17L5 17L5 16L6 16L6 17L7 17L7 18L6 18L6 19L5 19L5 20L6 20L6 21L5 21L5 23L3 23L3 24L2 24L2 21L1 21L1 20L0 20L0 22L1 22L1 25L0 25L0 26L1 26L1 27L0 27L0 29L2 29L2 30L0 30L0 31L2 31L2 32L4 32L4 35L3 35L3 33L2 33L2 34L1 34L1 32L0 32L0 37L1 37L1 38L0 38L0 39L1 39L1 40L0 40L0 41L1 41L1 43L0 43L0 46L1 46L1 47L2 47L2 46L3 46L3 44L5 44L5 43L4 43L4 42L3 42L3 41L4 41L4 40L3 40L3 39L4 39L4 38L5 38L5 37L7 37L7 38L6 38L6 39L5 39L5 40L6 40L6 41L8 41L8 42L6 42L6 43L7 43L7 44L6 44L6 45L7 45L7 46L6 46L6 47L8 47L8 48L9 48L9 49L12 49L12 51L13 51L13 49L12 49L12 48L14 48L14 46L13 46L13 44L12 44L12 43L14 43L14 42L15 42L15 40L16 40L16 41L17 41L17 42L18 42L18 41L19 41L19 40L21 40L21 41L20 41L20 42L19 42L19 43L18 43L18 44L17 44L17 43L15 43L15 49L14 49L14 51L15 51L15 49L16 49L16 51L17 51L17 48L18 48L18 49L19 49L19 50L18 50L18 52L17 52L17 54L18 54L18 52L19 52L19 53L20 53L20 55L16 55L16 54L15 54L15 53L16 53L16 52L15 52L15 53L14 53L14 52L13 52L13 53L14 53L14 54L12 54L12 53L11 53L11 52L10 52L10 51L11 51L11 50L10 50L10 51L9 51L9 52L8 52L8 51L7 51L7 50L8 50L8 49L7 49L7 48L5 48L5 49L4 49L4 47L3 47L3 48L0 48L0 49L4 49L4 50L3 50L3 51L4 51L4 52L2 52L2 50L1 50L1 51L0 51L0 53L1 53L1 54L0 54L0 56L2 56L2 55L1 55L1 54L3 54L3 55L4 55L4 56L3 56L3 57L4 57L4 59L2 59L2 58L1 58L1 57L0 57L0 58L1 58L1 60L0 60L0 61L1 61L1 63L0 63L0 66L1 66L1 68L0 68L0 75L1 75L1 76L0 76L0 77L1 77L1 78L0 78L0 79L1 79L1 80L0 80L0 83L1 83L1 82L2 82L2 81L1 81L1 80L4 80L4 84L3 84L3 83L2 83L2 84L0 84L0 85L1 85L1 87L2 87L2 88L1 88L1 89L0 89L0 90L1 90L1 89L2 89L2 88L3 88L3 90L4 90L4 91L0 91L0 92L1 92L1 93L0 93L0 95L1 95L1 94L3 94L3 97L4 97L4 93L7 93L7 92L4 92L4 91L5 91L5 90L4 90L4 88L5 88L5 87L7 87L7 88L6 88L6 89L7 89L7 90L6 90L6 91L7 91L7 90L8 90L8 92L9 92L9 93L8 93L8 94L9 94L9 95L7 95L7 94L5 94L5 95L7 95L7 96L6 96L6 97L5 97L5 98L6 98L6 99L7 99L7 100L4 100L4 99L3 99L3 100L2 100L2 97L0 97L0 101L1 101L1 103L2 103L2 104L0 104L0 107L1 107L1 106L2 106L2 108L1 108L1 109L3 109L3 106L2 106L2 105L4 105L4 107L5 107L5 108L6 108L6 109L8 109L8 110L9 110L9 111L8 111L8 113L9 113L9 111L10 111L10 112L11 112L11 113L10 113L10 114L9 114L9 115L8 115L8 117L10 117L10 114L11 114L11 113L12 113L12 112L13 112L13 113L15 113L15 114L16 114L16 115L15 115L15 116L17 116L17 117L20 117L20 116L22 116L22 114L23 114L23 115L24 115L24 114L25 114L25 116L26 116L26 114L27 114L27 111L26 111L26 108L27 108L27 109L28 109L28 108L30 108L30 109L29 109L29 110L28 110L28 113L29 113L29 112L30 112L30 113L32 113L32 114L31 114L31 115L30 115L30 114L29 114L29 115L27 115L27 116L29 116L29 117L34 117L34 116L32 116L32 115L33 115L33 113L35 113L35 114L34 114L34 115L38 115L38 114L39 114L39 115L40 115L40 117L42 117L42 116L43 116L43 117L44 117L44 115L47 115L47 114L48 114L48 113L47 113L47 114L45 114L45 113L46 113L46 112L44 112L44 110L46 110L46 111L50 111L50 112L49 112L49 113L50 113L50 112L51 112L51 113L52 113L52 112L55 112L55 115L53 115L53 114L54 114L54 113L53 113L53 114L52 114L52 115L50 115L50 114L49 114L49 115L50 115L50 117L52 117L52 116L54 116L54 117L57 117L57 116L56 116L56 115L62 115L62 113L63 113L63 115L64 115L64 116L66 116L66 117L69 117L69 116L70 116L70 117L71 117L71 115L72 115L72 114L74 114L74 113L75 113L75 115L74 115L74 116L73 116L73 117L77 117L77 115L76 115L76 113L77 113L77 114L81 114L81 112L82 112L82 116L83 116L83 115L85 115L85 116L84 116L84 117L85 117L85 116L86 116L86 117L91 117L91 116L92 116L92 114L91 114L91 115L89 115L89 114L90 114L90 112L92 112L92 113L94 113L94 111L97 111L97 112L99 112L99 115L97 115L97 114L98 114L98 113L95 113L95 114L94 114L94 115L93 115L93 117L96 117L96 116L94 116L94 115L97 115L97 116L98 116L98 117L100 117L100 116L101 116L101 117L104 117L104 115L103 115L103 116L102 116L102 115L101 115L101 114L105 114L105 113L107 113L107 114L106 114L106 115L105 115L105 116L107 116L107 117L113 117L113 114L112 114L112 116L110 116L110 115L108 115L108 113L109 113L109 114L110 114L110 113L113 113L113 109L114 109L114 111L115 111L115 112L114 112L114 113L115 113L115 114L114 114L114 115L115 115L115 114L117 114L117 113L116 113L116 112L117 112L117 111L116 111L116 109L117 109L117 108L116 108L116 109L114 109L114 106L113 106L113 108L111 108L111 107L109 107L109 106L112 106L112 104L111 104L111 103L110 103L110 102L109 102L109 100L108 100L108 101L107 101L107 99L108 99L108 98L109 98L109 99L110 99L110 98L109 98L109 96L110 96L110 97L111 97L111 98L112 98L112 99L113 99L113 98L114 98L114 96L113 96L113 95L115 95L115 92L113 92L113 90L112 90L112 89L114 89L114 91L116 91L116 90L115 90L115 89L114 89L114 87L115 87L115 88L116 88L116 89L117 89L117 88L116 88L116 86L117 86L117 85L116 85L116 86L115 86L115 85L113 85L113 84L114 84L114 82L111 82L111 81L112 81L112 80L111 80L111 77L112 77L112 76L111 76L111 75L112 75L112 74L115 74L115 76L113 76L113 77L114 77L114 79L115 79L115 82L116 82L116 80L117 80L117 79L116 79L116 78L115 78L115 76L116 76L116 77L117 77L117 76L116 76L116 74L115 74L115 73L112 73L112 72L114 72L114 71L115 71L115 72L116 72L116 73L117 73L117 72L116 72L116 70L117 70L117 68L116 68L116 69L114 69L114 71L113 71L113 70L112 70L112 67L111 67L111 65L112 65L112 66L113 66L113 67L117 67L117 65L116 65L116 64L117 64L117 63L116 63L116 62L114 62L114 60L113 60L113 59L115 59L115 57L117 57L117 55L115 55L115 57L114 57L114 55L113 55L113 51L112 51L112 50L113 50L113 49L114 49L114 50L115 50L115 52L114 52L114 53L117 53L117 51L116 51L116 50L117 50L117 47L116 47L116 46L117 46L117 43L116 43L116 44L115 44L115 45L113 45L113 46L115 46L115 47L116 47L116 48L113 48L113 47L112 47L112 48L110 48L110 47L107 47L107 44L109 44L109 42L112 42L112 44L111 44L111 43L110 43L110 45L111 45L111 46L112 46L112 44L113 44L113 43L115 43L115 41L117 41L117 39L116 39L116 37L115 37L115 41L112 41L112 40L113 40L113 39L112 39L112 40L111 40L111 39L110 39L110 41L109 41L109 42L108 42L108 43L107 43L107 41L106 41L106 40L108 40L108 39L107 39L107 37L109 37L109 36L110 36L110 37L111 37L111 38L112 38L112 37L113 37L113 31L115 31L115 33L114 33L114 34L115 34L115 36L116 36L116 33L117 33L117 32L116 32L116 30L115 30L115 29L117 29L117 27L116 27L116 26L117 26L117 24L116 24L116 22L117 22L117 20L116 20L116 18L117 18L117 16L116 16L116 14L117 14L117 12L116 12L116 10L115 10L115 9L117 9L117 8L113 8L113 10L112 10L112 8L111 8L111 9L109 9L109 8L108 8L108 7L109 7L109 6L108 6L108 5L107 5L107 4L106 4L106 3L109 3L109 1L108 1L108 2L106 2L106 1L107 1L107 0L106 0L106 1L105 1L105 3L104 3L104 2L103 2L103 0L102 0L102 3L101 3L101 2L100 2L100 3L99 3L99 1L100 1L100 0L95 0L95 1L96 1L96 2L97 2L97 3L95 3L95 2L94 2L94 0L92 0L92 1L91 1L91 0L89 0L89 1L88 1L88 3L85 3L85 4L84 4L84 3L80 3L80 2L79 2L79 1L76 1L76 0L74 0L74 2L75 2L75 1L76 1L76 3L74 3L74 8L72 8L72 11L74 11L74 12L75 12L75 13L74 13L74 14L73 14L73 12L72 12L72 13L70 13L70 12L69 12L69 13L67 13L67 12L68 12L68 11L66 11L66 12L65 12L65 9L66 9L66 8L67 8L67 10L68 10L68 9L69 9L69 8L70 8L70 9L71 9L71 6L72 6L72 7L73 7L73 6L72 6L72 5L71 5L71 4L72 4L72 1L71 1L71 2L70 2L70 0L67 0L67 1L64 1L64 0L63 0L63 1L62 1L62 0L61 0L61 1L60 1L60 0L59 0L59 2L61 2L61 1L62 1L62 2L63 2L63 1L64 1L64 3L63 3L63 5L62 5L62 3L58 3L58 0L57 0L57 1L56 1L56 0L55 0L55 1L53 1L53 2L52 2L52 3L51 3L51 1L50 1L50 0L49 0L49 1L48 1L48 0L46 0L46 1L45 1L45 0L44 0L44 2L45 2L45 4L44 4L44 3L43 3L43 4L41 4L41 6L40 6L40 8L41 8L41 10L42 10L42 8L41 8L41 6L42 6L42 7L43 7L43 12L44 12L44 13L42 13L42 14L44 14L44 13L45 13L45 12L44 12L44 11L45 11L45 10L46 10L46 15L45 15L45 16L44 16L44 15L41 15L41 14L40 14L40 13L38 13L38 10L37 10L37 9L35 9L35 8L36 8L36 7L37 7L37 6L36 6L36 5L38 5L38 4L40 4L40 3L41 3L41 2L43 2L43 0L38 0L38 2L40 2L40 3L35 3L35 2L34 2L34 1L35 1L35 0L31 0L31 1L30 1L30 0L29 0L29 1L27 1L27 0L26 0L26 2L28 2L28 3L27 3L27 4L28 4L28 5L26 5L26 7L27 7L27 8L26 8L26 9L24 9L24 7L25 7L25 6L24 6L24 7L23 7L23 6L22 6L22 7L21 7L21 6L20 6L20 4L21 4L21 5L23 5L23 4L22 4L22 3L23 3L23 1L22 1L22 0L18 0L18 2L17 2L17 3L16 3L16 2L15 2L15 1L16 1L16 0L15 0L15 1L14 1L14 0L13 0L13 1L14 1L14 2L13 2L13 3L15 3L15 4L18 4L18 3L20 3L20 4L19 4L19 5L18 5L18 7L17 7L17 6L16 6L16 7L15 7L15 5L13 5L13 4L11 4L11 3L12 3L12 2L9 2L9 1L10 1L10 0ZM36 0L36 1L37 1L37 0ZM80 0L80 1L81 1L81 0ZM83 0L83 2L85 2L85 0ZM86 0L86 2L87 2L87 0ZM19 1L19 2L20 2L20 3L22 3L22 1L21 1L21 2L20 2L20 1ZM24 1L24 3L25 3L25 1ZM31 1L31 2L29 2L29 3L28 3L28 4L29 4L29 5L28 5L28 6L27 6L27 7L28 7L28 6L29 6L29 7L30 7L30 6L29 6L29 5L30 5L30 4L29 4L29 3L31 3L31 4L32 4L32 3L34 3L34 4L35 4L35 5L36 5L36 4L35 4L35 3L34 3L34 2L33 2L33 1ZM40 1L40 2L41 2L41 1ZM46 1L46 2L47 2L47 3L46 3L46 4L45 4L45 5L43 5L43 7L44 7L44 10L45 10L45 7L46 7L46 6L47 6L47 8L46 8L46 9L47 9L47 10L48 10L48 11L47 11L47 13L48 13L48 15L46 15L46 17L45 17L45 18L44 18L44 17L42 17L42 18L40 18L40 19L39 19L39 16L40 16L40 14L38 14L38 15L37 15L37 14L36 14L36 15L35 15L35 16L34 16L34 14L33 14L33 13L32 13L32 12L34 12L34 13L35 13L35 12L36 12L36 13L37 13L37 10L35 10L35 9L34 9L34 10L33 10L33 9L31 9L31 11L30 11L30 12L31 12L31 13L29 13L29 12L28 12L28 11L29 11L29 8L28 8L28 9L26 9L26 11L25 11L25 10L22 10L22 9L23 9L23 7L22 7L22 8L20 8L20 9L21 9L21 10L18 10L18 12L17 12L17 11L14 11L14 12L15 12L15 14L17 14L17 13L18 13L18 16L17 16L17 15L14 15L14 16L12 16L12 14L11 14L11 13L12 13L12 12L13 12L13 11L12 11L12 12L11 12L11 11L10 11L10 12L11 12L11 13L8 13L8 11L9 11L9 10L8 10L8 11L5 11L5 12L7 12L7 13L4 13L4 14L3 14L3 17L4 17L4 16L5 16L5 15L6 15L6 16L7 16L7 17L8 17L8 18L10 18L10 19L9 19L9 20L8 20L8 19L6 19L6 20L8 20L8 21L6 21L6 22L9 22L9 20L10 20L10 21L12 21L12 22L16 22L16 23L19 23L19 24L20 24L20 23L19 23L19 22L20 22L20 21L19 21L19 20L18 20L18 19L20 19L20 20L21 20L21 22L22 22L22 21L23 21L23 20L22 20L22 19L24 19L24 20L25 20L25 19L27 19L27 20L28 20L28 19L29 19L29 20L30 20L30 19L32 19L32 21L33 21L33 20L34 20L34 22L33 22L33 23L32 23L32 22L30 22L30 23L29 23L29 21L28 21L28 22L26 22L26 21L24 21L24 23L23 23L23 24L25 24L25 22L26 22L26 24L28 24L28 23L29 23L29 25L28 25L28 26L26 26L26 25L25 25L25 26L26 26L26 28L25 28L25 30L24 30L24 28L23 28L23 29L21 29L21 30L18 30L18 29L20 29L20 28L22 28L22 27L24 27L24 25L23 25L23 26L22 26L22 24L21 24L21 25L17 25L17 26L16 26L16 25L14 25L14 23L12 23L12 26L11 26L11 25L10 25L10 23L11 23L11 22L10 22L10 23L9 23L9 24L7 24L7 23L5 23L5 25L2 25L2 26L4 26L4 27L2 27L2 29L4 29L4 28L5 28L5 29L6 29L6 30L9 30L9 35L5 35L5 36L7 36L7 37L8 37L8 36L9 36L9 38L10 38L10 40L8 40L8 41L10 41L10 40L11 40L11 41L14 41L14 40L15 40L15 39L14 39L14 38L13 38L13 40L12 40L12 38L10 38L10 34L12 34L12 35L11 35L11 37L12 37L12 35L14 35L14 36L13 36L13 37L15 37L15 36L17 36L17 37L16 37L16 38L17 38L17 39L16 39L16 40L17 40L17 41L18 41L18 40L17 40L17 39L18 39L18 38L17 38L17 37L18 37L18 35L19 35L19 34L20 34L20 33L24 33L24 35L23 35L23 34L22 34L22 35L23 35L23 37L22 37L22 36L21 36L21 35L20 35L20 37L19 37L19 38L20 38L20 37L21 37L21 39L27 39L27 40L25 40L25 41L26 41L26 42L27 42L27 43L25 43L25 44L24 44L24 40L22 40L22 41L21 41L21 42L20 42L20 43L19 43L19 44L18 44L18 46L17 46L17 44L16 44L16 48L17 48L17 47L18 47L18 46L19 46L19 44L20 44L20 45L21 45L21 42L22 42L22 43L23 43L23 45L22 45L22 46L21 46L21 47L20 47L20 48L22 48L22 47L23 47L23 48L24 48L24 47L25 47L25 49L26 49L26 52L25 52L25 53L24 53L24 52L23 52L23 49L22 49L22 50L21 50L21 49L20 49L20 50L19 50L19 52L20 52L20 53L21 53L21 52L23 52L23 53L24 53L24 54L23 54L23 55L20 55L20 56L18 56L18 57L17 57L17 56L16 56L16 57L17 57L17 58L16 58L16 60L15 60L15 61L16 61L16 62L17 62L17 58L18 58L18 57L20 57L20 58L22 58L22 57L23 57L23 58L25 58L25 57L26 57L26 56L25 56L25 53L26 53L26 55L27 55L27 57L28 57L28 56L31 56L31 54L32 54L32 55L33 55L33 56L34 56L34 55L33 55L33 54L34 54L34 51L35 51L35 52L39 52L39 54L38 54L38 53L35 53L35 56L37 56L37 55L38 55L38 56L39 56L39 57L35 57L35 58L36 58L36 59L35 59L35 60L37 60L37 61L35 61L35 63L34 63L34 61L33 61L33 63L30 63L30 60L29 60L29 61L28 61L28 59L27 59L27 58L26 58L26 59L25 59L25 62L26 62L26 64L27 64L27 66L26 66L26 65L25 65L25 64L24 64L24 62L22 62L22 61L23 61L23 59L21 59L21 60L20 60L20 59L18 59L18 62L19 62L19 63L20 63L20 64L21 64L21 65L20 65L20 66L21 66L21 68L20 68L20 69L19 69L19 67L18 67L18 65L19 65L19 64L18 64L18 63L17 63L17 64L14 64L14 63L15 63L15 62L14 62L14 60L13 60L13 59L14 59L14 58L15 58L15 55L14 55L14 56L9 56L9 55L10 55L10 54L11 54L11 55L12 55L12 54L11 54L11 53L10 53L10 52L9 52L9 53L6 53L6 52L7 52L7 51L6 51L6 50L7 50L7 49L6 49L6 50L4 50L4 51L6 51L6 52L4 52L4 53L3 53L3 54L4 54L4 55L5 55L5 56L9 56L9 61L8 61L8 62L7 62L7 61L5 61L5 62L4 62L4 64L7 64L7 63L10 63L10 61L11 61L11 60L13 60L13 61L12 61L12 62L11 62L11 65L12 65L12 64L14 64L14 65L13 65L13 66L8 66L8 67L12 67L12 68L10 68L10 69L9 69L9 68L7 68L7 67L6 67L6 66L7 66L7 65L5 65L5 66L4 66L4 67L6 67L6 68L7 68L7 69L4 69L4 70L3 70L3 69L1 69L1 73L3 73L3 74L1 74L1 75L5 75L5 74L7 74L7 75L6 75L6 76L5 76L5 77L3 77L3 78L2 78L2 79L3 79L3 78L7 78L7 77L8 77L8 76L9 76L9 77L10 77L10 75L11 75L11 79L6 79L6 80L8 80L8 81L6 81L6 82L9 82L9 85L10 85L10 86L9 86L9 87L10 87L10 88L7 88L7 89L10 89L10 88L11 88L11 90L10 90L10 91L9 91L9 92L11 92L11 93L12 93L12 92L11 92L11 90L12 90L12 89L13 89L13 88L14 88L14 89L15 89L15 90L16 90L16 89L15 89L15 88L17 88L17 86L18 86L18 85L19 85L19 86L20 86L20 87L24 87L24 90L25 90L25 91L22 91L22 92L21 92L21 90L23 90L23 89L21 89L21 90L20 90L20 88L19 88L19 89L18 89L18 90L17 90L17 91L16 91L16 92L17 92L17 93L16 93L16 94L15 94L15 93L14 93L14 94L15 94L15 95L14 95L14 96L12 96L12 94L11 94L11 96L12 96L12 97L13 97L13 98L12 98L12 100L11 100L11 98L10 98L10 100L9 100L9 101L8 101L8 100L7 100L7 101L6 101L6 102L5 102L5 103L4 103L4 102L3 102L3 101L4 101L4 100L3 100L3 101L2 101L2 100L1 100L1 101L2 101L2 102L3 102L3 104L5 104L5 106L7 106L7 107L6 107L6 108L7 108L7 107L8 107L8 109L9 109L9 110L10 110L10 111L11 111L11 112L12 112L12 110L13 110L13 112L14 112L14 111L15 111L15 110L16 110L16 109L17 109L17 111L16 111L16 113L17 113L17 114L20 114L20 115L18 115L18 116L20 116L20 115L21 115L21 113L23 113L23 114L24 114L24 113L25 113L25 114L26 114L26 113L25 113L25 112L26 112L26 111L25 111L25 110L24 110L24 109L23 109L23 110L24 110L24 111L22 111L22 109L21 109L21 111L20 111L20 110L18 110L18 109L20 109L20 105L21 105L21 106L22 106L22 107L21 107L21 108L25 108L25 107L27 107L27 108L28 108L28 107L31 107L31 108L32 108L32 107L33 107L33 108L36 108L36 109L35 109L35 112L36 112L36 113L37 113L37 114L38 114L38 113L39 113L39 114L40 114L40 115L41 115L41 116L42 116L42 115L43 115L43 114L44 114L44 113L43 113L43 112L42 112L42 111L43 111L43 110L44 110L44 109L45 109L45 108L47 108L47 109L48 109L48 110L50 110L50 111L51 111L51 112L52 112L52 111L55 111L55 112L56 112L56 109L55 109L55 110L54 110L54 109L53 109L53 108L52 108L52 109L49 109L49 108L50 108L50 107L54 107L54 108L55 108L55 107L56 107L56 106L57 106L57 103L59 103L59 104L58 104L58 106L59 106L59 104L60 104L60 107L59 107L59 108L61 108L61 106L62 106L62 109L63 109L63 108L64 108L64 107L65 107L65 108L67 108L67 109L68 109L68 111L67 111L67 110L65 110L65 109L64 109L64 111L63 111L63 110L62 110L62 111L61 111L61 113L62 113L62 111L63 111L63 113L68 113L68 112L71 112L71 113L74 113L74 111L75 111L75 112L76 112L76 107L77 107L77 108L78 108L78 109L79 109L79 110L77 110L77 113L80 113L80 112L81 112L81 111L80 111L80 110L82 110L82 109L79 109L79 106L80 106L80 108L81 108L81 107L82 107L82 108L84 108L84 107L85 107L85 106L86 106L86 105L88 105L88 106L89 106L89 107L92 107L92 108L88 108L88 107L86 107L86 108L87 108L87 112L88 112L88 109L89 109L89 110L90 110L90 111L89 111L89 112L90 112L90 111L92 111L92 112L93 112L93 111L94 111L94 109L95 109L95 108L97 108L97 109L96 109L96 110L97 110L97 111L101 111L101 113L100 113L100 114L101 114L101 113L104 113L104 112L107 112L107 111L104 111L104 108L105 108L105 109L106 109L106 110L108 110L108 108L105 108L105 107L106 107L106 106L107 106L107 107L108 107L108 106L109 106L109 105L111 105L111 104L110 104L110 103L109 103L109 104L107 104L107 105L106 105L106 104L105 104L105 103L106 103L106 102L107 102L107 101L106 101L106 100L105 100L105 99L106 99L106 98L105 98L105 99L104 99L104 102L103 102L103 101L102 101L102 102L100 102L100 99L101 99L101 100L102 100L102 99L103 99L103 97L105 97L105 95L106 95L106 97L107 97L107 98L108 98L108 96L107 96L107 95L108 95L108 93L109 93L109 94L110 94L110 96L111 96L111 97L112 97L112 98L113 98L113 97L112 97L112 96L111 96L111 94L110 94L110 93L109 93L109 92L107 92L107 91L103 91L103 90L102 90L102 89L103 89L103 85L104 85L104 84L105 84L105 83L106 83L106 85L105 85L105 86L104 86L104 88L105 88L105 87L106 87L106 90L107 90L107 89L108 89L108 90L109 90L109 91L111 91L111 93L112 93L112 94L114 94L114 93L112 93L112 90L111 90L111 89L112 89L112 88L113 88L113 87L109 87L109 89L108 89L108 87L106 87L106 85L107 85L107 86L108 86L108 85L107 85L107 84L108 84L108 82L107 82L107 83L106 83L106 80L107 80L107 81L109 81L109 82L110 82L110 81L111 81L111 80L108 80L108 78L107 78L107 77L108 77L108 76L107 76L107 75L103 75L103 72L101 72L101 73L100 73L100 74L99 74L99 73L97 73L97 75L96 75L96 74L95 74L95 75L94 75L94 74L93 74L93 75L91 75L91 74L92 74L92 73L91 73L91 72L89 72L89 73L90 73L90 74L87 74L87 73L88 73L88 71L86 71L86 70L87 70L87 69L88 69L88 70L89 70L89 71L92 71L92 72L93 72L93 71L94 71L94 70L93 70L93 68L94 68L94 69L95 69L95 71L96 71L96 72L97 72L97 71L96 71L96 69L97 69L97 70L98 70L98 71L104 71L104 73L105 73L105 74L106 74L106 73L107 73L107 72L105 72L105 71L106 71L106 68L105 68L105 67L106 67L106 65L108 65L108 64L109 64L109 63L110 63L110 64L112 64L112 65L113 65L113 66L114 66L114 65L115 65L115 63L114 63L114 62L112 62L112 61L111 61L111 63L110 63L110 61L109 61L109 63L108 63L108 61L106 61L106 60L107 60L107 59L103 59L103 58L101 58L101 57L100 57L100 55L99 55L99 54L98 54L98 53L97 53L97 51L99 51L99 52L100 52L100 51L99 51L99 50L102 50L102 51L103 51L103 50L104 50L104 49L105 49L105 47L106 47L106 46L105 46L105 47L104 47L104 48L103 48L103 46L104 46L104 45L102 45L102 44L103 44L103 43L104 43L104 44L107 44L107 43L104 43L104 40L105 40L105 39L106 39L106 38L105 38L105 39L104 39L104 40L103 40L103 38L104 38L104 37L102 37L102 38L101 38L101 36L102 36L102 35L103 35L103 34L102 34L102 35L101 35L101 36L100 36L100 35L99 35L99 34L100 34L100 33L104 33L104 34L105 34L105 35L104 35L104 36L105 36L105 35L106 35L106 34L107 34L107 35L108 35L108 36L109 36L109 35L108 35L108 32L107 32L107 31L108 31L108 29L107 29L107 31L104 31L104 30L106 30L106 28L107 28L107 27L106 27L106 26L105 26L105 25L106 25L106 24L107 24L107 25L108 25L108 26L109 26L109 25L108 25L108 23L109 23L109 21L107 21L107 20L108 20L108 19L109 19L109 18L110 18L110 20L111 20L111 21L112 21L112 20L113 20L113 19L112 19L112 18L114 18L114 21L113 21L113 22L114 22L114 21L115 21L115 22L116 22L116 21L115 21L115 18L114 18L114 17L113 17L113 16L115 16L115 13L114 13L114 12L115 12L115 10L114 10L114 11L113 11L113 13L111 13L111 11L112 11L112 10L111 10L111 11L110 11L110 13L108 13L108 14L107 14L107 13L106 13L106 14L105 14L105 15L104 15L104 14L101 14L101 12L100 12L100 11L99 11L99 10L101 10L101 11L103 11L103 10L104 10L104 12L105 12L105 11L106 11L106 12L107 12L107 11L108 11L108 12L109 12L109 11L108 11L108 10L107 10L107 7L108 7L108 6L107 6L107 7L106 7L106 4L105 4L105 5L104 5L104 3L103 3L103 7L102 7L102 6L101 6L101 9L100 9L100 5L99 5L99 10L98 10L98 9L97 9L97 7L98 7L98 6L97 6L97 5L98 5L98 4L93 4L93 3L94 3L94 2L93 2L93 1L92 1L92 3L91 3L91 4L92 4L92 5L91 5L91 7L90 7L90 6L89 6L89 7L90 7L90 8L87 8L87 9L88 9L88 10L90 10L90 11L89 11L89 12L88 12L88 11L87 11L87 10L86 10L86 9L82 9L82 10L84 10L84 11L83 11L83 12L82 12L82 11L80 11L80 12L79 12L79 13L78 13L78 12L76 12L76 11L75 11L75 9L76 9L76 10L77 10L77 11L79 11L79 10L80 10L80 9L81 9L81 7L82 7L82 6L81 6L81 7L80 7L80 5L79 5L79 7L80 7L80 8L79 8L79 9L76 9L76 6L77 6L77 7L78 7L78 6L77 6L77 5L76 5L76 4L75 4L75 8L74 8L74 9L73 9L73 10L74 10L74 11L75 11L75 12L76 12L76 15L75 15L75 14L74 14L74 15L72 15L72 14L71 14L71 17L70 17L70 18L69 18L69 19L67 19L67 17L68 17L68 16L70 16L70 13L69 13L69 14L68 14L68 15L67 15L67 14L66 14L66 13L65 13L65 12L64 12L64 10L63 10L63 9L64 9L64 8L66 8L66 6L65 6L65 5L67 5L67 7L68 7L68 8L69 8L69 7L70 7L70 6L71 6L71 5L70 5L70 4L71 4L71 3L70 3L70 4L69 4L69 5L67 5L67 4L68 4L68 3L69 3L69 1L68 1L68 3L67 3L67 2L65 2L65 3L64 3L64 5L63 5L63 8L62 8L62 5L61 5L61 10L60 10L60 11L59 11L59 13L58 13L58 12L57 12L57 11L58 11L58 10L59 10L59 9L58 9L58 10L56 10L56 4L58 4L58 3L57 3L57 2L56 2L56 4L55 4L55 10L54 10L54 11L53 11L53 8L54 8L54 6L53 6L53 5L54 5L54 3L53 3L53 4L52 4L52 5L51 5L51 7L50 7L50 5L49 5L49 4L50 4L50 3L49 3L49 2L50 2L50 1L49 1L49 2L48 2L48 1ZM97 1L97 2L98 2L98 1ZM31 2L31 3L32 3L32 2ZM77 2L77 4L78 4L78 2ZM48 3L48 4L49 4L49 3ZM88 3L88 5L90 5L90 4L89 4L89 3ZM101 4L101 5L102 5L102 4ZM31 5L31 8L34 8L34 5ZM45 5L45 6L44 6L44 7L45 7L45 6L46 6L46 5ZM47 5L47 6L48 6L48 7L49 7L49 5ZM57 5L57 8L60 8L60 5ZM69 5L69 6L68 6L68 7L69 7L69 6L70 6L70 5ZM83 5L83 8L86 8L86 5ZM95 5L95 6L94 6L94 7L95 7L95 10L94 10L94 12L93 12L93 13L92 13L92 12L91 12L91 11L93 11L93 9L94 9L94 8L92 8L92 7L93 7L93 6L92 6L92 7L91 7L91 11L90 11L90 12L91 12L91 13L89 13L89 14L91 14L91 16L92 16L92 17L90 17L90 16L89 16L89 15L88 15L88 16L89 16L89 17L88 17L88 18L89 18L89 19L88 19L88 21L89 21L89 19L90 19L90 20L91 20L91 22L92 22L92 23L91 23L91 24L92 24L92 25L90 25L90 23L89 23L89 22L88 22L88 25L87 25L87 27L88 27L88 28L86 28L86 24L87 24L87 23L86 23L86 22L84 22L84 21L85 21L85 20L84 20L84 19L86 19L86 20L87 20L87 19L86 19L86 18L87 18L87 17L86 17L86 16L87 16L87 15L86 15L86 14L87 14L87 13L86 13L86 12L87 12L87 11L85 11L85 13L82 13L82 12L80 12L80 13L79 13L79 14L80 14L80 13L81 13L81 16L80 16L80 15L78 15L78 13L77 13L77 15L76 15L76 16L75 16L75 15L74 15L74 17L72 17L72 18L70 18L70 20L69 20L69 21L68 21L68 20L67 20L67 19L65 19L65 16L66 16L66 15L65 15L65 13L64 13L64 14L63 14L63 15L64 15L64 17L63 17L63 19L61 19L61 20L59 20L59 21L56 21L56 22L58 22L58 23L59 23L59 24L58 24L58 25L57 25L57 24L56 24L56 23L55 23L55 25L56 25L56 26L58 26L58 27L60 27L60 28L61 28L61 29L62 29L62 30L63 30L63 31L61 31L61 30L60 30L60 29L59 29L59 30L58 30L58 29L57 29L57 27L56 27L56 28L55 28L55 27L50 27L50 26L51 26L51 25L48 25L48 24L47 24L47 25L46 25L46 27L45 27L45 28L46 28L46 31L45 31L45 32L47 32L47 33L48 33L48 34L49 34L49 36L50 36L50 38L51 38L51 37L53 37L53 36L55 36L55 37L56 37L56 38L55 38L55 39L54 39L54 40L55 40L55 43L54 43L54 44L55 44L55 45L56 45L56 44L57 44L57 45L61 45L61 47L62 47L62 48L59 48L59 47L60 47L60 46L58 46L58 50L55 50L55 51L54 51L54 52L53 52L53 50L54 50L54 49L56 49L56 48L57 48L57 47L56 47L56 48L54 48L54 47L53 47L53 46L54 46L54 45L52 45L52 44L53 44L53 43L52 43L52 42L51 42L51 43L49 43L49 42L50 42L50 40L51 40L51 41L52 41L52 40L53 40L53 38L52 38L52 40L51 40L51 39L49 39L49 41L48 41L48 43L47 43L47 42L46 42L46 41L44 41L44 42L46 42L46 44L44 44L44 43L42 43L42 42L43 42L43 40L44 40L44 39L40 39L40 37L39 37L39 36L37 36L37 35L38 35L38 33L40 33L40 32L41 32L41 34L42 34L42 32L43 32L43 34L46 34L46 35L45 35L45 36L47 36L47 34L46 34L46 33L44 33L44 31L42 31L42 32L41 32L41 31L40 31L40 32L38 32L38 30L36 30L36 31L37 31L37 32L35 32L35 29L37 29L37 28L38 28L38 29L40 29L40 30L42 30L42 29L43 29L43 30L44 30L44 25L45 25L45 24L44 24L44 23L45 23L45 22L46 22L46 23L47 23L47 22L46 22L46 21L47 21L47 20L46 20L46 19L49 19L49 21L48 21L48 22L49 22L49 23L50 23L50 24L51 24L51 23L52 23L52 26L54 26L54 24L53 24L53 23L54 23L54 22L55 22L55 19L56 19L56 20L57 20L57 19L60 19L60 18L62 18L62 16L61 16L61 17L60 17L60 16L59 16L59 14L58 14L58 15L57 15L57 13L56 13L56 12L55 12L55 11L54 11L54 12L53 12L53 11L50 11L50 9L52 9L52 8L53 8L53 6L52 6L52 8L50 8L50 9L48 9L48 10L49 10L49 11L48 11L48 12L49 12L49 13L50 13L50 14L49 14L49 17L48 17L48 16L47 16L47 17L48 17L48 18L46 18L46 19L45 19L45 20L44 20L44 19L43 19L43 20L41 20L41 19L40 19L40 20L41 20L41 22L44 22L44 23L40 23L40 24L44 24L44 25L43 25L43 28L41 28L41 27L40 27L40 26L39 26L39 27L38 27L38 24L39 24L39 23L38 23L38 22L39 22L39 21L36 21L36 20L35 20L35 19L36 19L36 18L34 18L34 17L32 17L32 13L31 13L31 14L29 14L29 15L26 15L26 13L28 13L28 12L27 12L27 11L26 11L26 12L25 12L25 11L24 11L24 12L25 12L25 15L24 15L24 13L20 13L20 12L21 12L21 11L22 11L22 10L21 10L21 11L20 11L20 12L19 12L19 13L20 13L20 15L19 15L19 16L20 16L20 15L21 15L21 14L23 14L23 16L21 16L21 18L22 18L22 17L23 17L23 16L24 16L24 18L25 18L25 17L26 17L26 18L27 18L27 19L28 19L28 17L32 17L32 19L34 19L34 20L35 20L35 21L36 21L36 22L37 22L37 23L38 23L38 24L36 24L36 25L37 25L37 26L36 26L36 27L37 27L37 28L35 28L35 29L31 29L31 28L32 28L32 27L31 27L31 28L29 28L29 27L30 27L30 26L31 26L31 25L32 25L32 26L34 26L34 27L33 27L33 28L34 28L34 27L35 27L35 26L34 26L34 25L35 25L35 22L34 22L34 25L33 25L33 24L32 24L32 23L31 23L31 24L30 24L30 25L29 25L29 26L28 26L28 28L27 28L27 29L30 29L30 31L27 31L27 32L26 32L26 30L25 30L25 32L26 32L26 33L25 33L25 35L24 35L24 36L25 36L25 37L23 37L23 38L25 38L25 37L26 37L26 38L28 38L28 37L29 37L29 39L28 39L28 40L27 40L27 41L29 41L29 42L28 42L28 44L29 44L29 47L28 47L28 48L26 48L26 49L28 49L28 52L26 52L26 53L29 53L29 54L27 54L27 55L30 55L30 53L31 53L31 52L32 52L32 54L33 54L33 52L32 52L32 51L30 51L30 49L28 49L28 48L29 48L29 47L30 47L30 48L31 48L31 49L36 49L36 50L37 50L37 51L38 51L38 50L40 50L40 48L42 48L42 47L43 47L43 48L44 48L44 50L46 50L46 51L48 51L48 49L47 49L47 47L48 47L48 43L49 43L49 45L50 45L50 46L49 46L49 48L50 48L50 49L52 49L52 50L51 50L51 51L52 51L52 52L53 52L53 53L52 53L52 54L53 54L53 55L52 55L52 56L53 56L53 55L54 55L54 57L52 57L52 58L51 58L51 57L50 57L50 54L51 54L51 53L50 53L50 54L49 54L49 53L47 53L47 52L46 52L46 53L47 53L47 54L43 54L43 53L44 53L44 52L45 52L45 51L40 51L40 56L42 56L42 55L41 55L41 52L42 52L42 54L43 54L43 56L44 56L44 55L47 55L47 54L49 54L49 57L47 57L47 58L46 58L46 57L43 57L43 58L46 58L46 59L45 59L45 60L44 60L44 59L43 59L43 60L44 60L44 61L45 61L45 62L44 62L44 63L42 63L42 64L43 64L43 65L41 65L41 63L40 63L40 62L39 62L39 63L40 63L40 65L41 65L41 66L43 66L43 69L42 69L42 70L44 70L44 71L43 71L43 72L42 72L42 71L41 71L41 69L40 69L40 68L42 68L42 67L39 67L39 69L40 69L40 74L41 74L41 73L42 73L42 74L43 74L43 73L44 73L44 75L38 75L38 72L39 72L39 71L38 71L38 70L37 70L37 69L38 69L38 68L35 68L35 67L36 67L36 66L37 66L37 67L38 67L38 66L39 66L39 65L38 65L38 61L37 61L37 62L36 62L36 63L37 63L37 64L35 64L35 65L34 65L34 64L33 64L33 65L32 65L32 64L31 64L31 65L29 65L29 67L28 67L28 68L33 68L33 69L32 69L32 70L31 70L31 69L29 69L29 71L28 71L28 70L27 70L27 68L26 68L26 66L25 66L25 67L24 67L24 68L23 68L23 69L24 69L24 68L26 68L26 70L23 70L23 71L22 71L22 69L21 69L21 70L19 70L19 71L18 71L18 69L15 69L15 68L17 68L17 65L18 65L18 64L17 64L17 65L15 65L15 66L13 66L13 67L15 67L15 68L12 68L12 69L11 69L11 70L12 70L12 71L10 71L10 72L11 72L11 73L12 73L12 74L11 74L11 75L13 75L13 76L12 76L12 77L14 77L14 78L12 78L12 80L13 80L13 79L14 79L14 80L15 80L15 81L12 81L12 82L14 82L14 83L13 83L13 85L12 85L12 83L11 83L11 82L10 82L10 85L12 85L12 86L10 86L10 87L12 87L12 88L13 88L13 87L14 87L14 88L15 88L15 86L14 86L14 85L16 85L16 86L17 86L17 84L19 84L19 85L20 85L20 86L21 86L21 84L22 84L22 86L23 86L23 84L22 84L22 82L23 82L23 81L24 81L24 82L25 82L25 84L24 84L24 85L25 85L25 84L26 84L26 86L24 86L24 87L26 87L26 88L25 88L25 89L26 89L26 90L27 90L27 91L28 91L28 92L27 92L27 94L28 94L28 92L30 92L30 91L28 91L28 89L32 89L32 88L30 88L30 87L33 87L33 91L32 91L32 92L31 92L31 93L32 93L32 94L33 94L33 96L32 96L32 95L28 95L28 96L29 96L29 97L30 97L30 96L31 96L31 98L30 98L30 99L28 99L28 100L26 100L26 101L25 101L25 100L23 100L23 103L25 103L25 104L26 104L26 106L27 106L27 107L28 107L28 106L27 106L27 105L28 105L28 104L30 104L30 103L31 103L31 104L34 104L34 105L33 105L33 107L36 107L36 106L37 106L37 108L38 108L38 104L37 104L37 105L36 105L36 104L35 104L35 103L40 103L40 106L39 106L39 107L40 107L40 109L41 109L41 108L42 108L42 107L41 107L41 105L42 105L42 103L40 103L40 102L38 102L38 101L36 101L36 100L35 100L35 99L38 99L38 100L39 100L39 99L38 99L38 97L39 97L39 98L40 98L40 97L41 97L41 95L42 95L42 94L43 94L43 96L44 96L44 95L45 95L45 97L43 97L43 99L44 99L44 98L46 98L46 99L45 99L45 100L44 100L44 101L41 101L41 99L40 99L40 101L41 101L41 102L43 102L43 103L45 103L45 104L46 104L46 105L45 105L45 106L46 106L46 107L48 107L48 108L49 108L49 107L50 107L50 106L49 106L49 105L51 105L51 103L52 103L52 105L53 105L53 104L54 104L54 105L55 105L55 106L56 106L56 103L57 103L57 102L58 102L58 101L59 101L59 102L60 102L60 100L59 100L59 99L58 99L58 97L59 97L59 92L58 92L58 93L57 93L57 95L58 95L58 96L57 96L57 99L55 99L55 100L54 100L54 99L52 99L52 101L51 101L51 99L50 99L50 98L51 98L51 95L52 95L52 97L53 97L53 95L52 95L52 93L53 93L53 94L56 94L56 92L57 92L57 91L56 91L56 92L55 92L55 91L52 91L52 90L58 90L58 88L60 88L60 89L59 89L59 91L60 91L60 90L61 90L61 91L64 91L64 92L65 92L65 93L64 93L64 94L65 94L65 95L64 95L64 96L63 96L63 95L61 95L61 96L60 96L60 97L61 97L61 99L62 99L62 100L63 100L63 101L64 101L64 102L63 102L63 103L65 103L65 104L64 104L64 105L63 105L63 104L62 104L62 101L61 101L61 104L62 104L62 106L66 106L66 103L65 103L65 101L64 101L64 100L65 100L65 99L64 99L64 100L63 100L63 99L62 99L62 98L64 98L64 97L65 97L65 98L67 98L67 97L65 97L65 95L66 95L66 96L67 96L67 95L69 95L69 96L68 96L68 97L69 97L69 98L70 98L70 99L69 99L69 100L68 100L68 99L67 99L67 100L68 100L68 101L67 101L67 102L68 102L68 101L69 101L69 102L70 102L70 104L68 104L68 103L67 103L67 106L68 106L68 105L69 105L69 106L70 106L70 107L69 107L69 109L70 109L70 111L72 111L72 110L71 110L71 109L74 109L74 110L73 110L73 111L74 111L74 110L75 110L75 108L72 108L72 107L74 107L74 106L73 106L73 105L70 105L70 104L71 104L71 102L72 102L72 103L74 103L74 105L75 105L75 106L76 106L76 105L77 105L77 104L78 104L78 105L79 105L79 103L81 103L81 105L80 105L80 106L82 106L82 107L83 107L83 106L85 106L85 103L83 103L83 102L82 102L82 101L86 101L86 102L88 102L88 100L89 100L89 102L91 102L91 104L92 104L92 102L91 102L91 101L92 101L92 100L91 100L91 99L93 99L93 98L94 98L94 100L93 100L93 102L94 102L94 103L95 103L95 102L94 102L94 100L95 100L95 99L97 99L97 100L96 100L96 102L97 102L97 104L96 104L96 106L97 106L97 108L98 108L98 106L100 106L100 104L102 104L102 103L100 103L100 104L99 104L99 103L98 103L98 102L99 102L99 99L100 99L100 97L99 97L99 95L98 95L98 94L100 94L100 96L101 96L101 99L102 99L102 96L103 96L103 95L105 95L105 94L106 94L106 95L107 95L107 92L105 92L105 93L103 93L103 92L102 92L102 91L100 91L100 92L98 92L98 90L97 90L97 89L96 89L96 87L97 87L97 88L99 88L99 87L98 87L98 86L100 86L100 84L101 84L101 86L102 86L102 83L101 83L101 82L102 82L102 81L103 81L103 84L104 84L104 82L105 82L105 81L103 81L103 80L104 80L104 79L103 79L103 80L101 80L101 79L98 79L98 81L96 81L96 82L97 82L97 84L96 84L96 85L97 85L97 86L96 86L96 87L95 87L95 86L94 86L94 87L93 87L93 88L94 88L94 87L95 87L95 89L94 89L94 92L92 92L92 90L93 90L93 89L92 89L92 87L91 87L91 88L89 88L89 85L88 85L88 84L89 84L89 83L90 83L90 85L92 85L92 86L93 86L93 84L94 84L94 85L95 85L95 84L94 84L94 83L95 83L95 81L94 81L94 83L93 83L93 81L92 81L92 82L91 82L91 83L90 83L90 82L89 82L89 81L91 81L91 80L92 80L92 79L89 79L89 78L87 78L87 77L88 77L88 76L89 76L89 77L90 77L90 78L91 78L91 75L87 75L87 74L86 74L86 75L85 75L85 73L83 73L83 70L84 70L84 71L85 71L85 69L87 69L87 67L86 67L86 65L87 65L87 66L88 66L88 65L89 65L89 66L91 66L91 65L93 65L93 64L92 64L92 63L93 63L93 62L94 62L94 64L95 64L95 65L94 65L94 66L93 66L93 67L98 67L98 68L97 68L97 69L98 69L98 70L100 70L100 69L101 69L101 67L100 67L100 65L101 65L101 66L102 66L102 65L104 65L104 63L101 63L101 62L100 62L100 64L99 64L99 65L98 65L98 64L97 64L97 66L95 66L95 65L96 65L96 64L95 64L95 62L97 62L97 63L98 63L98 61L96 61L96 60L95 60L95 61L94 61L94 60L92 60L92 59L93 59L93 56L92 56L92 58L91 58L91 56L90 56L90 55L88 55L88 56L86 56L86 55L85 55L85 53L86 53L86 51L87 51L87 49L88 49L88 52L87 52L87 53L88 53L88 52L90 52L90 53L89 53L89 54L90 54L90 53L91 53L91 51L93 51L93 50L92 50L92 49L94 49L94 53L93 53L93 52L92 52L92 54L91 54L91 55L96 55L96 54L97 54L97 56L98 56L98 57L96 57L96 56L95 56L95 57L96 57L96 59L97 59L97 60L99 60L99 59L97 59L97 58L98 58L98 57L99 57L99 55L98 55L98 54L97 54L97 53L96 53L96 52L95 52L95 51L97 51L97 49L98 49L98 50L99 50L99 49L100 49L100 48L101 48L101 49L102 49L102 50L103 50L103 48L101 48L101 47L100 47L100 44L102 44L102 43L100 43L100 42L101 42L101 41L96 41L96 40L95 40L95 39L96 39L96 38L94 38L94 37L93 37L93 36L92 36L92 35L90 35L90 34L91 34L91 33L92 33L92 34L93 34L93 32L94 32L94 33L95 33L95 34L94 34L94 35L95 35L95 34L96 34L96 35L98 35L98 38L97 38L97 39L98 39L98 40L99 40L99 39L98 39L98 38L100 38L100 40L102 40L102 42L103 42L103 40L102 40L102 39L101 39L101 38L100 38L100 37L99 37L99 35L98 35L98 34L99 34L99 31L100 31L100 32L101 32L101 31L102 31L102 32L104 32L104 31L102 31L102 30L101 30L101 31L100 31L100 29L101 29L101 28L102 28L102 29L103 29L103 30L104 30L104 29L105 29L105 28L104 28L104 27L103 27L103 28L102 28L102 27L100 27L100 28L99 28L99 27L98 27L98 26L96 26L96 27L98 27L98 30L97 30L97 28L96 28L96 31L98 31L98 34L96 34L96 32L94 32L94 31L95 31L95 30L94 30L94 31L93 31L93 29L95 29L95 28L94 28L94 27L95 27L95 26L94 26L94 25L93 25L93 24L92 24L92 23L96 23L96 22L97 22L97 23L98 23L98 22L99 22L99 21L100 21L100 24L101 24L101 21L102 21L102 20L103 20L103 21L104 21L104 22L103 22L103 24L102 24L102 25L101 25L101 26L103 26L103 24L104 24L104 25L105 25L105 24L106 24L106 23L107 23L107 21L106 21L106 20L107 20L107 19L108 19L108 18L109 18L109 14L108 14L108 17L107 17L107 14L106 14L106 15L105 15L105 16L106 16L106 17L104 17L104 18L103 18L103 16L104 16L104 15L103 15L103 16L102 16L102 15L101 15L101 14L100 14L100 12L99 12L99 11L97 11L97 13L96 13L96 12L95 12L95 10L96 10L96 7L97 7L97 6L96 6L96 5ZM13 6L13 7L14 7L14 8L13 8L13 9L12 9L12 10L14 10L14 9L15 9L15 10L17 10L17 7L16 7L16 9L15 9L15 7L14 7L14 6ZM19 6L19 7L18 7L18 9L19 9L19 7L20 7L20 6ZM32 6L32 7L33 7L33 6ZM35 6L35 7L36 7L36 6ZM38 6L38 7L39 7L39 6ZM58 6L58 7L59 7L59 6ZM64 6L64 7L65 7L65 6ZM84 6L84 7L85 7L85 6ZM87 6L87 7L88 7L88 6ZM95 6L95 7L96 7L96 6ZM104 6L104 7L103 7L103 8L102 8L102 9L101 9L101 10L103 10L103 8L104 8L104 7L105 7L105 6ZM6 9L6 10L7 10L7 9ZM104 9L104 10L105 10L105 9ZM34 10L34 11L35 11L35 10ZM61 10L61 11L62 11L62 13L60 13L60 15L62 15L62 13L63 13L63 11L62 11L62 10ZM69 10L69 11L71 11L71 10ZM106 10L106 11L107 11L107 10ZM0 11L0 12L2 12L2 11ZM31 11L31 12L32 12L32 11ZM40 11L40 12L41 12L41 11ZM49 11L49 12L50 12L50 11ZM16 12L16 13L17 13L17 12ZM51 12L51 13L52 13L52 14L50 14L50 17L51 17L51 18L49 18L49 19L52 19L52 18L53 18L53 20L52 20L52 21L51 21L51 22L50 22L50 21L49 21L49 22L50 22L50 23L51 23L51 22L53 22L53 21L54 21L54 19L55 19L55 18L56 18L56 19L57 19L57 18L58 18L58 16L54 16L54 15L56 15L56 13L54 13L54 14L53 14L53 13L52 13L52 12ZM94 12L94 13L93 13L93 15L92 15L92 16L93 16L93 17L92 17L92 18L90 18L90 19L92 19L92 22L93 22L93 20L94 20L94 21L95 21L95 22L96 22L96 21L97 21L97 22L98 22L98 20L100 20L100 21L101 21L101 20L100 20L100 19L103 19L103 18L101 18L101 17L100 17L100 15L99 15L99 13L97 13L97 14L98 14L98 15L95 15L95 16L93 16L93 15L94 15L94 13L95 13L95 14L96 14L96 13L95 13L95 12ZM0 13L0 15L1 15L1 13ZM13 13L13 14L14 14L14 13ZM85 13L85 14L86 14L86 13ZM91 13L91 14L92 14L92 13ZM110 13L110 15L111 15L111 16L112 16L112 15L113 15L113 14L114 14L114 13L113 13L113 14L112 14L112 15L111 15L111 13ZM6 14L6 15L7 15L7 16L9 16L9 17L11 17L11 15L10 15L10 14L9 14L9 15L8 15L8 14ZM52 14L52 16L51 16L51 17L53 17L53 18L55 18L55 17L53 17L53 14ZM82 14L82 15L84 15L84 16L86 16L86 15L84 15L84 14ZM9 15L9 16L10 16L10 15ZM25 15L25 16L26 16L26 17L27 17L27 16L26 16L26 15ZM29 15L29 16L31 16L31 15ZM77 15L77 16L76 16L76 18L74 18L74 19L75 19L75 21L71 21L71 20L73 20L73 19L71 19L71 20L70 20L70 22L69 22L69 23L67 23L67 22L68 22L68 21L67 21L67 20L65 20L65 21L64 21L64 22L65 22L65 24L66 24L66 23L67 23L67 25L66 25L66 27L65 27L65 25L63 25L63 24L64 24L64 23L63 23L63 22L62 22L62 21L63 21L63 20L64 20L64 19L63 19L63 20L61 20L61 21L59 21L59 23L60 23L60 25L58 25L58 26L60 26L60 27L61 27L61 26L60 26L60 25L61 25L61 23L60 23L60 22L62 22L62 23L63 23L63 24L62 24L62 25L63 25L63 26L62 26L62 29L63 29L63 30L64 30L64 31L63 31L63 32L61 32L61 34L62 34L62 36L63 36L63 37L65 37L65 40L64 40L64 38L62 38L62 39L61 39L61 37L60 37L60 39L59 39L59 41L60 41L60 42L58 42L58 43L57 43L57 42L56 42L56 43L57 43L57 44L58 44L58 43L60 43L60 42L61 42L61 43L62 43L62 44L61 44L61 45L62 45L62 47L63 47L63 48L64 48L64 47L65 47L65 48L67 48L67 49L66 49L66 50L65 50L65 49L64 49L64 50L63 50L63 49L62 49L62 52L61 52L61 53L60 53L60 51L59 51L59 50L60 50L60 49L59 49L59 50L58 50L58 51L59 51L59 53L57 53L57 52L56 52L56 51L55 51L55 52L56 52L56 53L57 53L57 54L56 54L56 55L57 55L57 56L58 56L58 54L61 54L61 53L62 53L62 55L60 55L60 56L61 56L61 58L62 58L62 59L61 59L61 62L62 62L62 63L59 63L59 64L58 64L58 63L57 63L57 62L56 62L56 61L51 61L51 62L50 62L50 60L52 60L52 59L51 59L51 58L50 58L50 60L49 60L49 58L47 58L47 59L48 59L48 60L49 60L49 62L48 62L48 61L47 61L47 60L45 60L45 61L46 61L46 62L45 62L45 63L44 63L44 64L45 64L45 65L47 65L47 67L46 67L46 66L44 66L44 65L43 65L43 66L44 66L44 70L45 70L45 71L44 71L44 72L45 72L45 71L46 71L46 74L45 74L45 75L44 75L44 76L45 76L45 78L44 78L44 77L43 77L43 76L38 76L38 79L36 79L36 78L35 78L35 77L36 77L36 76L37 76L37 72L38 72L38 71L36 71L36 69L35 69L35 68L34 68L34 65L33 65L33 66L32 66L32 65L31 65L31 66L30 66L30 67L33 67L33 68L34 68L34 69L33 69L33 70L32 70L32 71L33 71L33 70L35 70L35 71L36 71L36 75L35 75L35 74L34 74L34 73L35 73L35 72L34 72L34 73L33 73L33 72L31 72L31 70L30 70L30 73L33 73L33 74L32 74L32 75L31 75L31 76L30 76L30 74L28 74L28 73L29 73L29 72L28 72L28 71L27 71L27 70L26 70L26 72L25 72L25 71L23 71L23 73L22 73L22 72L21 72L21 71L20 71L20 72L19 72L19 73L18 73L18 71L17 71L17 70L15 70L15 69L12 69L12 70L15 70L15 71L12 71L12 72L13 72L13 75L14 75L14 74L16 74L16 76L17 76L17 77L16 77L16 78L14 78L14 79L18 79L18 80L19 80L19 81L18 81L18 83L19 83L19 84L20 84L20 83L19 83L19 82L21 82L21 81L20 81L20 80L22 80L22 79L24 79L24 80L26 80L26 81L25 81L25 82L26 82L26 84L28 84L28 86L26 86L26 87L29 87L29 84L28 84L28 83L30 83L30 82L29 82L29 81L31 81L31 82L32 82L32 79L31 79L31 78L32 78L32 77L31 77L31 76L33 76L33 75L34 75L34 76L35 76L35 77L33 77L33 78L34 78L34 79L33 79L33 80L34 80L34 81L33 81L33 82L34 82L34 81L35 81L35 80L36 80L36 82L35 82L35 83L36 83L36 84L37 84L37 85L38 85L38 87L39 87L39 88L38 88L38 92L37 92L37 87L36 87L36 85L35 85L35 87L34 87L34 90L35 90L35 92L37 92L37 93L34 93L34 96L33 96L33 97L34 97L34 98L32 98L32 100L34 100L34 98L35 98L35 97L36 97L36 96L37 96L37 95L35 95L35 94L38 94L38 96L39 96L39 97L40 97L40 96L39 96L39 95L41 95L41 94L39 94L39 93L40 93L40 92L41 92L41 93L42 93L42 91L43 91L43 90L45 90L45 91L44 91L44 93L43 93L43 94L45 94L45 95L46 95L46 94L47 94L47 93L50 93L50 95L49 95L49 94L48 94L48 95L47 95L47 96L46 96L46 97L47 97L47 96L50 96L50 95L51 95L51 93L50 93L50 92L52 92L52 91L51 91L51 90L52 90L52 89L54 89L54 88L52 88L52 87L55 87L55 88L56 88L56 89L57 89L57 87L56 87L56 86L53 86L53 85L56 85L56 81L57 81L57 80L56 80L56 79L57 79L57 78L59 78L59 77L61 77L61 78L60 78L60 79L61 79L61 82L62 82L62 83L61 83L61 86L62 86L62 87L60 87L60 88L61 88L61 89L62 89L62 90L63 90L63 89L64 89L64 90L65 90L65 89L68 89L68 90L66 90L66 91L65 91L65 92L66 92L66 91L68 91L68 93L65 93L65 94L66 94L66 95L67 95L67 94L69 94L69 95L70 95L70 96L72 96L72 97L73 97L73 98L72 98L72 99L70 99L70 101L72 101L72 100L73 100L73 101L75 101L75 102L74 102L74 103L79 103L79 101L81 101L81 99L80 99L80 98L77 98L77 100L76 100L76 98L74 98L74 97L76 97L76 95L75 95L75 94L78 94L78 95L77 95L77 97L80 97L80 96L81 96L81 95L83 95L83 97L81 97L81 98L82 98L82 100L86 100L86 101L87 101L87 100L88 100L88 99L89 99L89 100L90 100L90 99L91 99L91 98L90 98L90 97L91 97L91 96L88 96L88 95L92 95L92 96L93 96L93 97L92 97L92 98L93 98L93 97L94 97L94 98L95 98L95 97L94 97L94 96L93 96L93 95L94 95L94 94L97 94L97 93L96 93L96 92L97 92L97 90L96 90L96 91L95 91L95 92L94 92L94 94L93 94L93 93L92 93L92 92L91 92L91 93L92 93L92 94L90 94L90 92L88 92L88 91L86 91L86 93L85 93L85 90L86 90L86 89L84 89L84 87L82 87L82 88L83 88L83 89L84 89L84 90L83 90L83 91L84 91L84 92L83 92L83 93L81 93L81 91L82 91L82 89L80 89L80 88L81 88L81 86L82 86L82 85L81 85L81 86L80 86L80 84L82 84L82 82L83 82L83 81L84 81L84 80L85 80L85 81L86 81L86 82L87 82L87 84L88 84L88 82L87 82L87 81L86 81L86 78L83 78L83 77L84 77L84 76L85 76L85 77L86 77L86 76L87 76L87 75L86 75L86 76L85 76L85 75L84 75L84 74L83 74L83 73L82 73L82 72L81 72L81 71L79 71L79 70L77 70L77 71L79 71L79 72L78 72L78 74L77 74L77 76L78 76L78 78L77 78L77 77L76 77L76 69L78 69L78 67L80 67L80 68L79 68L79 69L80 69L80 70L81 70L81 69L82 69L82 70L83 70L83 69L85 69L85 67L83 67L83 69L82 69L82 68L81 68L81 67L82 67L82 66L83 66L83 65L84 65L84 64L85 64L85 65L86 65L86 64L85 64L85 63L87 63L87 65L88 65L88 63L90 63L90 64L89 64L89 65L91 65L91 63L92 63L92 62L93 62L93 61L90 61L90 62L88 62L88 61L89 61L89 60L87 60L87 61L86 61L86 62L85 62L85 63L83 63L83 61L82 61L82 62L81 62L81 63L80 63L80 62L79 62L79 61L78 61L78 60L79 60L79 59L80 59L80 60L81 60L81 59L80 59L80 58L82 58L82 56L84 56L84 55L83 55L83 54L84 54L84 53L83 53L83 54L82 54L82 56L81 56L81 55L80 55L80 54L81 54L81 53L82 53L82 52L83 52L83 50L82 50L82 52L81 52L81 45L80 45L80 44L82 44L82 43L83 43L83 45L82 45L82 49L83 49L83 48L85 48L85 46L86 46L86 49L85 49L85 51L86 51L86 49L87 49L87 48L88 48L88 47L87 47L87 46L88 46L88 45L84 45L84 38L83 38L83 36L85 36L85 35L82 35L82 37L80 37L80 40L79 40L79 42L78 42L78 41L77 41L77 40L76 40L76 39L79 39L79 38L76 38L76 37L77 37L77 36L78 36L78 35L79 35L79 34L80 34L80 36L81 36L81 34L82 34L82 32L81 32L81 31L80 31L80 32L81 32L81 33L78 33L78 32L79 32L79 30L80 30L80 29L79 29L79 28L78 28L78 30L77 30L77 28L76 28L76 27L75 27L75 26L74 26L74 25L73 25L73 24L74 24L74 23L77 23L77 24L78 24L78 25L79 25L79 26L77 26L77 27L79 27L79 26L80 26L80 25L81 25L81 24L82 24L82 26L83 26L83 27L82 27L82 30L84 30L84 29L85 29L85 30L86 30L86 28L85 28L85 27L84 27L84 26L83 26L83 25L85 25L85 23L84 23L84 22L83 22L83 24L82 24L82 23L80 23L80 22L81 22L81 20L82 20L82 21L83 21L83 19L84 19L84 17L83 17L83 16L82 16L82 17L80 17L80 16L78 16L78 15ZM15 16L15 17L14 17L14 18L11 18L11 20L12 20L12 21L13 21L13 20L14 20L14 21L15 21L15 20L14 20L14 18L15 18L15 19L16 19L16 21L18 21L18 22L19 22L19 21L18 21L18 20L17 20L17 19L18 19L18 18L19 18L19 17L18 17L18 18L15 18L15 17L17 17L17 16ZM35 16L35 17L37 17L37 20L39 20L39 19L38 19L38 16ZM95 16L95 17L93 17L93 18L92 18L92 19L93 19L93 18L94 18L94 20L95 20L95 19L96 19L96 18L95 18L95 17L97 17L97 16ZM98 16L98 18L97 18L97 19L98 19L98 18L99 18L99 16ZM56 17L56 18L57 18L57 17ZM59 17L59 18L60 18L60 17ZM79 17L79 18L80 18L80 17ZM82 17L82 18L81 18L81 19L83 19L83 17ZM106 17L106 18L104 18L104 21L105 21L105 23L104 23L104 24L105 24L105 23L106 23L106 21L105 21L105 20L106 20L106 18L107 18L107 17ZM111 17L111 18L112 18L112 17ZM29 18L29 19L30 19L30 18ZM76 18L76 20L77 20L77 19L78 19L78 21L79 21L79 22L80 22L80 21L79 21L79 20L80 20L80 19L78 19L78 18ZM111 19L111 20L112 20L112 19ZM43 20L43 21L44 21L44 20ZM45 20L45 21L46 21L46 20ZM3 21L3 22L4 22L4 21ZM66 21L66 22L67 22L67 21ZM76 21L76 22L77 22L77 21ZM70 22L70 23L69 23L69 24L70 24L70 23L72 23L72 24L73 24L73 23L74 23L74 22L73 22L73 23L72 23L72 22ZM79 23L79 25L80 25L80 23ZM110 23L110 24L111 24L111 23ZM112 23L112 25L110 25L110 28L109 28L109 29L110 29L110 28L111 28L111 27L112 27L112 30L113 30L113 28L116 28L116 27L115 27L115 25L114 25L114 24L115 24L115 23ZM6 24L6 25L7 25L7 24ZM96 24L96 25L99 25L99 26L100 26L100 25L99 25L99 24ZM41 25L41 26L42 26L42 25ZM69 25L69 27L70 27L70 28L69 28L69 29L70 29L70 31L69 31L69 30L68 30L68 27L66 27L66 28L65 28L65 27L64 27L64 26L63 26L63 27L64 27L64 28L63 28L63 29L65 29L65 30L68 30L68 31L67 31L67 34L66 34L66 35L64 35L64 34L63 34L63 35L64 35L64 36L66 36L66 35L67 35L67 37L66 37L66 38L67 38L67 37L69 37L69 38L70 38L70 39L69 39L69 43L73 43L73 45L72 45L72 44L71 44L71 46L70 46L70 48L71 48L71 49L67 49L67 51L66 51L66 52L67 52L67 54L68 54L68 53L70 53L70 51L71 51L71 50L73 50L73 52L72 52L72 53L73 53L73 54L72 54L72 55L71 55L71 54L69 54L69 55L71 55L71 56L72 56L72 55L74 55L74 56L73 56L73 57L71 57L71 58L70 58L70 63L71 63L71 62L72 62L72 63L73 63L73 65L74 65L74 66L73 66L73 67L74 67L74 66L75 66L75 65L76 65L76 64L77 64L77 66L76 66L76 68L77 68L77 66L78 66L78 65L79 65L79 66L80 66L80 65L81 65L81 66L82 66L82 63L81 63L81 64L80 64L80 63L77 63L77 61L76 61L76 60L77 60L77 59L76 59L76 57L77 57L77 54L78 54L78 53L79 53L79 52L80 52L80 53L81 53L81 52L80 52L80 51L79 51L79 52L78 52L78 53L77 53L77 51L75 51L75 52L76 52L76 57L75 57L75 55L74 55L74 54L75 54L75 53L73 53L73 52L74 52L74 50L75 50L75 47L74 47L74 46L73 46L73 45L75 45L75 46L76 46L76 48L77 48L77 46L78 46L78 45L79 45L79 47L78 47L78 50L79 50L79 49L80 49L80 45L79 45L79 44L80 44L80 43L79 43L79 44L78 44L78 43L76 43L76 41L75 41L75 43L76 43L76 45L75 45L75 44L74 44L74 42L73 42L73 41L74 41L74 40L73 40L73 41L72 41L72 40L71 40L71 37L72 37L72 38L73 38L73 39L74 39L74 38L73 38L73 36L71 36L71 37L70 37L70 36L68 36L68 31L69 31L69 33L70 33L70 35L71 35L71 34L73 34L73 35L74 35L74 34L73 34L73 32L74 32L74 33L75 33L75 35L76 35L76 36L75 36L75 37L76 37L76 36L77 36L77 35L78 35L78 34L77 34L77 32L78 32L78 31L76 31L76 32L75 32L75 31L73 31L73 32L72 32L72 33L70 33L70 31L72 31L72 30L71 30L71 29L73 29L73 30L74 30L74 29L75 29L75 30L76 30L76 29L75 29L75 28L71 28L71 27L72 27L72 25L71 25L71 26L70 26L70 25ZM88 25L88 26L89 26L89 27L90 27L90 28L89 28L89 29L88 29L88 30L89 30L89 29L90 29L90 31L89 31L89 32L90 32L90 31L91 31L91 32L93 32L93 31L92 31L92 29L93 29L93 27L94 27L94 26L93 26L93 27L92 27L92 28L91 28L91 26L90 26L90 25ZM112 25L112 27L113 27L113 25ZM5 26L5 28L6 28L6 29L8 29L8 27L10 27L10 28L9 28L9 30L11 30L11 33L12 33L12 34L14 34L14 35L15 35L15 34L14 34L14 33L12 33L12 32L14 32L14 30L13 30L13 29L14 29L14 27L15 27L15 26L12 26L12 28L11 28L11 27L10 27L10 26ZM17 26L17 27L18 27L18 28L15 28L15 30L16 30L16 29L18 29L18 28L20 28L20 27L19 27L19 26ZM21 26L21 27L22 27L22 26ZM73 26L73 27L74 27L74 26ZM6 27L6 28L7 28L7 27ZM39 27L39 28L40 28L40 29L41 29L41 28L40 28L40 27ZM46 27L46 28L49 28L49 29L50 29L50 30L51 30L51 31L48 31L48 29L47 29L47 32L48 32L48 33L49 33L49 32L51 32L51 33L50 33L50 36L52 36L52 35L51 35L51 34L53 34L53 35L55 35L55 36L56 36L56 32L55 32L55 31L56 31L56 30L57 30L57 29L54 29L54 28L50 28L50 27ZM80 27L80 28L81 28L81 27ZM83 27L83 28L84 28L84 27ZM10 28L10 29L11 29L11 28ZM12 28L12 29L13 29L13 28ZM70 28L70 29L71 29L71 28ZM90 28L90 29L91 29L91 28ZM51 29L51 30L52 30L52 29ZM12 30L12 31L13 31L13 30ZM22 30L22 32L24 32L24 30ZM98 30L98 31L99 31L99 30ZM5 31L5 34L8 34L8 31ZM15 31L15 33L16 33L16 34L17 34L17 35L18 35L18 34L19 34L19 31L18 31L18 32L16 32L16 31ZM31 31L31 34L34 34L34 31ZM51 31L51 32L54 32L54 33L53 33L53 34L55 34L55 32L54 32L54 31ZM57 31L57 34L60 34L60 31ZM64 31L64 33L66 33L66 32L65 32L65 31ZM83 31L83 34L86 34L86 31ZM87 31L87 33L88 33L88 35L86 35L86 36L88 36L88 37L87 37L87 38L86 38L86 37L85 37L85 39L86 39L86 42L85 42L85 43L86 43L86 44L87 44L87 41L88 41L88 43L89 43L89 48L90 48L90 49L89 49L89 51L91 51L91 50L90 50L90 49L92 49L92 48L93 48L93 47L94 47L94 46L93 46L93 47L91 47L91 46L90 46L90 45L92 45L92 44L93 44L93 45L97 45L97 44L98 44L98 45L99 45L99 42L98 42L98 43L97 43L97 42L96 42L96 41L95 41L95 40L94 40L94 41L92 41L92 40L93 40L93 39L94 39L94 38L93 38L93 37L92 37L92 38L90 38L90 37L89 37L89 36L90 36L90 35L89 35L89 33L88 33L88 31ZM109 31L109 34L112 34L112 31ZM6 32L6 33L7 33L7 32ZM27 32L27 33L26 33L26 35L25 35L25 36L26 36L26 35L27 35L27 36L28 36L28 35L29 35L29 36L30 36L30 35L29 35L29 34L30 34L30 33L29 33L29 34L28 34L28 32ZM32 32L32 33L33 33L33 32ZM58 32L58 33L59 33L59 32ZM84 32L84 33L85 33L85 32ZM110 32L110 33L111 33L111 32ZM17 33L17 34L18 34L18 33ZM27 34L27 35L28 35L28 34ZM36 34L36 35L35 35L35 37L33 37L33 35L31 35L31 37L30 37L30 39L29 39L29 41L30 41L30 39L31 39L31 42L32 42L32 39L31 39L31 37L32 37L32 38L33 38L33 41L34 41L34 42L36 42L36 43L35 43L35 48L37 48L37 46L38 46L38 48L39 48L39 46L40 46L40 47L42 47L42 46L43 46L43 44L42 44L42 45L41 45L41 44L40 44L40 45L39 45L39 43L40 43L40 42L39 42L39 41L40 41L40 39L39 39L39 37L37 37L37 36L36 36L36 35L37 35L37 34ZM40 35L40 36L41 36L41 35ZM43 35L43 36L44 36L44 35ZM60 35L60 36L61 36L61 35ZM88 35L88 36L89 36L89 35ZM111 35L111 37L112 37L112 35ZM1 36L1 37L2 37L2 36ZM3 36L3 38L4 38L4 36ZM58 36L58 38L57 38L57 39L56 39L56 40L57 40L57 41L58 41L58 38L59 38L59 36ZM106 36L106 37L107 37L107 36ZM35 37L35 38L36 38L36 37ZM42 37L42 38L43 38L43 37ZM45 37L45 39L46 39L46 40L47 40L47 39L48 39L48 38L49 38L49 37L47 37L47 38L46 38L46 37ZM1 38L1 39L2 39L2 38ZM37 38L37 39L38 39L38 38ZM75 38L75 39L76 39L76 38ZM81 38L81 40L80 40L80 42L81 42L81 43L82 43L82 42L81 42L81 40L82 40L82 41L83 41L83 40L82 40L82 39L83 39L83 38ZM87 38L87 39L88 39L88 40L89 40L89 41L90 41L90 42L89 42L89 43L90 43L90 44L92 44L92 43L93 43L93 44L95 44L95 41L94 41L94 43L93 43L93 42L92 42L92 41L91 41L91 40L92 40L92 39L91 39L91 40L89 40L89 39L88 39L88 38ZM6 39L6 40L7 40L7 39ZM34 39L34 40L35 40L35 41L36 41L36 42L37 42L37 44L36 44L36 45L38 45L38 46L39 46L39 45L38 45L38 41L39 41L39 40L35 40L35 39ZM60 39L60 41L62 41L62 42L63 42L63 41L64 41L64 40L61 40L61 39ZM66 39L66 41L65 41L65 42L66 42L66 41L67 41L67 40L68 40L68 39ZM1 40L1 41L2 41L2 40ZM70 40L70 42L72 42L72 41L71 41L71 40ZM22 41L22 42L23 42L23 41ZM53 41L53 42L54 42L54 41ZM2 42L2 43L1 43L1 45L2 45L2 44L3 44L3 42ZM9 42L9 43L8 43L8 44L7 44L7 45L8 45L8 47L9 47L9 48L10 48L10 47L11 47L11 48L12 48L12 46L11 46L11 44L10 44L10 42ZM90 42L90 43L92 43L92 42ZM33 43L33 44L34 44L34 43ZM51 43L51 44L50 44L50 45L51 45L51 46L50 46L50 47L51 47L51 48L52 48L52 49L53 49L53 48L52 48L52 47L51 47L51 46L52 46L52 45L51 45L51 44L52 44L52 43ZM63 43L63 45L64 45L64 46L65 46L65 45L66 45L66 46L67 46L67 45L70 45L70 44L68 44L68 43L67 43L67 44L66 44L66 43ZM9 44L9 45L10 45L10 44ZM25 44L25 45L23 45L23 47L24 47L24 46L25 46L25 47L26 47L26 44ZM30 44L30 46L32 46L32 45L31 45L31 44ZM46 44L46 45L45 45L45 46L44 46L44 48L46 48L46 46L47 46L47 44ZM77 44L77 45L76 45L76 46L77 46L77 45L78 45L78 44ZM27 45L27 46L28 46L28 45ZM33 45L33 46L34 46L34 45ZM83 45L83 47L84 47L84 45ZM108 45L108 46L109 46L109 45ZM115 45L115 46L116 46L116 45ZM68 46L68 47L67 47L67 48L68 48L68 47L69 47L69 46ZM71 46L71 48L72 48L72 49L73 49L73 50L74 50L74 49L73 49L73 48L74 48L74 47L72 47L72 46ZM95 46L95 47L96 47L96 48L97 48L97 47L96 47L96 46ZM98 46L98 47L99 47L99 48L98 48L98 49L99 49L99 48L100 48L100 47L99 47L99 46ZM33 47L33 48L34 48L34 47ZM94 48L94 49L95 49L95 50L96 50L96 49L95 49L95 48ZM107 48L107 49L108 49L108 50L107 50L107 51L109 51L109 53L110 53L110 55L108 55L108 56L107 56L107 55L106 55L106 54L108 54L108 52L107 52L107 53L106 53L106 50L105 50L105 51L104 51L104 54L103 54L103 53L100 53L100 54L101 54L101 56L103 56L103 55L104 55L104 56L106 56L106 57L107 57L107 58L108 58L108 56L112 56L112 53L110 53L110 52L111 52L111 49L110 49L110 48ZM37 49L37 50L38 50L38 49ZM41 49L41 50L43 50L43 49ZM46 49L46 50L47 50L47 49ZM76 49L76 50L77 50L77 49ZM20 50L20 52L21 52L21 50ZM24 50L24 51L25 51L25 50ZM49 50L49 51L50 51L50 50ZM64 50L64 52L63 52L63 54L64 54L64 55L63 55L63 56L62 56L62 57L64 57L64 56L65 56L65 58L63 58L63 59L62 59L62 60L63 60L63 61L65 61L65 62L68 62L68 61L69 61L69 60L68 60L68 59L69 59L69 58L68 58L68 55L66 55L66 56L65 56L65 54L64 54L64 53L65 53L65 50ZM109 50L109 51L110 51L110 50ZM68 51L68 52L69 52L69 51ZM1 52L1 53L2 53L2 52ZM29 52L29 53L30 53L30 52ZM4 53L4 54L5 54L5 53ZM53 53L53 54L54 54L54 55L55 55L55 54L54 54L54 53ZM94 53L94 54L95 54L95 53ZM105 53L105 54L106 54L106 53ZM6 54L6 55L8 55L8 54ZM23 55L23 57L24 57L24 55ZM79 55L79 56L78 56L78 57L79 57L79 58L80 58L80 57L81 57L81 56L80 56L80 55ZM21 56L21 57L22 57L22 56ZM69 56L69 57L70 57L70 56ZM79 56L79 57L80 57L80 56ZM89 56L89 57L88 57L88 58L87 58L87 59L91 59L91 58L90 58L90 56ZM5 57L5 60L8 60L8 57ZM10 57L10 58L12 58L12 57ZM13 57L13 58L14 58L14 57ZM31 57L31 60L34 60L34 57ZM39 57L39 59L38 59L38 58L37 58L37 59L38 59L38 60L39 60L39 61L41 61L41 62L42 62L42 61L41 61L41 60L42 60L42 59L41 59L41 58L40 58L40 57ZM54 57L54 58L53 58L53 60L54 60L54 59L55 59L55 57ZM57 57L57 60L60 60L60 57ZM66 57L66 58L65 58L65 59L67 59L67 57ZM73 57L73 58L72 58L72 59L71 59L71 60L73 60L73 59L75 59L75 60L76 60L76 59L75 59L75 57ZM83 57L83 60L86 60L86 57ZM104 57L104 58L105 58L105 57ZM109 57L109 60L112 60L112 57ZM113 57L113 58L114 58L114 57ZM6 58L6 59L7 59L7 58ZM32 58L32 59L33 59L33 58ZM58 58L58 59L59 59L59 58ZM84 58L84 59L85 59L85 58ZM94 58L94 59L95 59L95 58ZM100 58L100 60L102 60L102 59L101 59L101 58ZM110 58L110 59L111 59L111 58ZM10 59L10 60L11 60L11 59ZM26 59L26 61L27 61L27 59ZM39 59L39 60L40 60L40 59ZM63 59L63 60L64 60L64 59ZM1 60L1 61L2 61L2 62L3 62L3 60ZM65 60L65 61L66 61L66 60ZM104 60L104 61L102 61L102 62L104 62L104 61L105 61L105 60ZM115 60L115 61L117 61L117 60ZM19 61L19 62L20 62L20 63L21 63L21 64L23 64L23 63L22 63L22 62L20 62L20 61ZM31 61L31 62L32 62L32 61ZM73 61L73 62L74 62L74 65L75 65L75 64L76 64L76 63L75 63L75 62L76 62L76 61ZM6 62L6 63L7 63L7 62ZM12 62L12 63L13 63L13 62ZM28 62L28 64L29 64L29 62ZM46 62L46 63L45 63L45 64L46 64L46 63L48 63L48 62ZM49 62L49 64L47 64L47 65L49 65L49 66L48 66L48 67L50 67L50 70L49 70L49 68L48 68L48 69L47 69L47 68L46 68L46 69L45 69L45 70L46 70L46 71L47 71L47 72L48 72L48 73L47 73L47 74L46 74L46 75L47 75L47 74L49 74L49 75L50 75L50 76L49 76L49 77L50 77L50 78L51 78L51 77L52 77L52 76L53 76L53 78L52 78L52 80L50 80L50 79L49 79L49 80L50 80L50 81L49 81L49 82L48 82L48 80L47 80L47 78L48 78L48 77L46 77L46 79L45 79L45 80L46 80L46 81L45 81L45 82L46 82L46 83L45 83L45 84L44 84L44 79L43 79L43 80L42 80L42 81L40 81L40 82L42 82L42 81L43 81L43 83L42 83L42 84L41 84L41 83L37 83L37 84L40 84L40 86L39 86L39 87L40 87L40 88L39 88L39 91L41 91L41 90L43 90L43 89L44 89L44 87L43 87L43 89L42 89L42 87L40 87L40 86L41 86L41 85L42 85L42 86L44 86L44 85L46 85L46 86L47 86L47 87L45 87L45 90L46 90L46 91L50 91L50 90L51 90L51 87L50 87L50 86L49 86L49 87L50 87L50 88L47 88L47 87L48 87L48 86L47 86L47 85L51 85L51 84L52 84L52 85L53 85L53 84L55 84L55 83L51 83L51 84L46 84L46 83L50 83L50 81L52 81L52 82L55 82L55 81L53 81L53 78L55 78L55 79L56 79L56 77L58 77L58 76L59 76L59 75L62 75L62 74L61 74L61 72L59 72L59 71L58 71L58 70L57 70L57 69L58 69L58 67L59 67L59 68L60 68L60 69L59 69L59 70L61 70L61 71L62 71L62 72L63 72L63 71L64 71L64 73L65 73L65 71L66 71L66 72L68 72L68 71L66 71L66 69L67 69L67 70L69 70L69 71L71 71L71 72L69 72L69 73L70 73L70 74L72 74L72 75L74 75L74 77L73 77L73 78L76 78L76 80L75 80L75 79L73 79L73 80L74 80L74 81L77 81L77 82L79 82L79 83L78 83L78 85L79 85L79 84L80 84L80 83L81 83L81 82L82 82L82 80L83 80L83 79L82 79L82 77L83 77L83 76L84 76L84 75L83 75L83 74L82 74L82 73L79 73L79 76L80 76L80 74L81 74L81 76L82 76L82 77L81 77L81 78L80 78L80 79L79 79L79 80L80 80L80 79L82 79L82 80L81 80L81 82L80 82L80 81L77 81L77 80L78 80L78 79L77 79L77 78L76 78L76 77L75 77L75 75L74 75L74 74L72 74L72 73L73 73L73 72L75 72L75 71L73 71L73 72L72 72L72 70L75 70L75 69L74 69L74 68L73 68L73 69L71 69L71 70L70 70L70 67L72 67L72 64L71 64L71 65L69 65L69 66L70 66L70 67L69 67L69 68L68 68L68 67L66 67L66 69L65 69L65 66L67 66L67 65L68 65L68 64L69 64L69 63L67 63L67 65L65 65L65 64L66 64L66 63L64 63L64 62L63 62L63 63L62 63L62 64L61 64L61 65L60 65L60 64L59 64L59 65L58 65L58 64L57 64L57 69L56 69L56 68L55 68L55 69L56 69L56 70L54 70L54 71L52 71L52 72L51 72L51 73L52 73L52 72L53 72L53 73L54 73L54 74L55 74L55 75L53 75L53 74L52 74L52 76L51 76L51 75L50 75L50 72L48 72L48 71L50 71L50 70L53 70L53 69L54 69L54 67L53 67L53 66L55 66L55 67L56 67L56 64L55 64L55 63L56 63L56 62L55 62L55 63L54 63L54 65L51 65L51 63L52 63L52 64L53 64L53 63L52 63L52 62L51 62L51 63L50 63L50 62ZM87 62L87 63L88 63L88 62ZM106 62L106 63L105 63L105 65L106 65L106 64L107 64L107 62ZM1 63L1 65L2 65L2 64L3 64L3 63ZM63 63L63 65L61 65L61 66L62 66L62 67L61 67L61 68L62 68L62 71L63 71L63 69L64 69L64 63ZM112 63L112 64L113 64L113 63ZM8 64L8 65L9 65L9 64ZM23 65L23 66L24 66L24 65ZM37 65L37 66L38 66L38 65ZM59 65L59 67L60 67L60 65ZM2 66L2 67L3 67L3 66ZM50 66L50 67L51 67L51 69L52 69L52 68L53 68L53 67L51 67L51 66ZM98 66L98 67L99 67L99 68L98 68L98 69L100 69L100 67L99 67L99 66ZM107 66L107 68L108 68L108 69L107 69L107 71L108 71L108 72L109 72L109 78L110 78L110 77L111 77L111 76L110 76L110 75L111 75L111 74L112 74L112 73L111 73L111 72L112 72L112 71L110 71L110 69L111 69L111 68L110 68L110 67L108 67L108 66ZM88 67L88 69L90 69L90 70L91 70L91 68L92 68L92 67ZM103 67L103 69L102 69L102 70L103 70L103 69L104 69L104 71L105 71L105 69L104 69L104 67ZM67 68L67 69L68 69L68 68ZM80 68L80 69L81 69L81 68ZM95 68L95 69L96 69L96 68ZM46 69L46 70L47 70L47 71L48 71L48 70L47 70L47 69ZM108 69L108 71L109 71L109 72L110 72L110 71L109 71L109 69ZM4 70L4 71L3 71L3 72L4 72L4 73L7 73L7 74L10 74L10 73L9 73L9 70L8 70L8 71L7 71L7 70ZM56 70L56 71L57 71L57 70ZM64 70L64 71L65 71L65 70ZM6 71L6 72L7 72L7 71ZM15 71L15 73L17 73L17 74L18 74L18 75L19 75L19 76L20 76L20 77L19 77L19 80L20 80L20 77L21 77L21 76L24 76L24 78L25 78L25 79L26 79L26 80L27 80L27 79L28 79L28 81L29 81L29 80L31 80L31 79L30 79L30 77L29 77L29 78L27 78L27 79L26 79L26 77L28 77L28 76L29 76L29 75L28 75L28 74L27 74L27 75L26 75L26 74L25 74L25 73L23 73L23 75L21 75L21 76L20 76L20 75L19 75L19 74L20 74L20 73L19 73L19 74L18 74L18 73L17 73L17 72L16 72L16 71ZM54 71L54 73L55 73L55 74L56 74L56 76L55 76L55 77L56 77L56 76L58 76L58 75L59 75L59 72L58 72L58 73L57 73L57 72L56 72L56 73L55 73L55 71ZM26 72L26 73L27 73L27 72ZM86 72L86 73L87 73L87 72ZM56 73L56 74L57 74L57 75L58 75L58 74L57 74L57 73ZM66 73L66 74L68 74L68 73ZM101 73L101 74L100 74L100 76L99 76L99 75L97 75L97 76L96 76L96 77L97 77L97 76L98 76L98 77L99 77L99 78L100 78L100 76L102 76L102 77L101 77L101 78L104 78L104 77L105 77L105 76L104 76L104 77L103 77L103 75L101 75L101 74L102 74L102 73ZM110 73L110 74L111 74L111 73ZM24 75L24 76L25 76L25 77L26 77L26 76L25 76L25 75ZM27 75L27 76L28 76L28 75ZM64 75L64 76L63 76L63 77L62 77L62 76L61 76L61 77L62 77L62 78L61 78L61 79L62 79L62 81L63 81L63 79L62 79L62 78L63 78L63 77L64 77L64 80L66 80L66 81L65 81L65 82L63 82L63 83L64 83L64 84L62 84L62 85L64 85L64 86L68 86L68 87L62 87L62 88L68 88L68 89L69 89L69 90L68 90L68 91L69 91L69 92L71 92L71 93L69 93L69 94L70 94L70 95L72 95L72 94L73 94L73 95L74 95L74 94L73 94L73 93L72 93L72 92L71 92L71 90L72 90L72 91L74 91L74 90L73 90L73 89L75 89L75 87L76 87L76 88L77 88L77 89L78 89L78 90L80 90L80 91L79 91L79 94L80 94L80 91L81 91L81 90L80 90L80 89L79 89L79 88L80 88L80 86L79 86L79 88L78 88L78 87L76 87L76 86L77 86L77 83L76 83L76 82L75 82L75 83L74 83L74 82L73 82L73 81L72 81L72 80L71 80L71 81L70 81L70 79L71 79L71 75L69 75L69 76L68 76L68 75ZM82 75L82 76L83 76L83 75ZM93 75L93 79L96 79L96 78L94 78L94 77L95 77L95 76L94 76L94 75ZM1 76L1 77L2 77L2 76ZM6 76L6 77L7 77L7 76ZM14 76L14 77L15 77L15 76ZM17 77L17 78L18 78L18 77ZM40 77L40 78L39 78L39 79L40 79L40 80L41 80L41 77ZM42 77L42 78L43 78L43 77ZM65 77L65 79L66 79L66 80L67 80L67 82L66 82L66 83L65 83L65 84L64 84L64 85L66 85L66 84L67 84L67 85L69 85L69 87L68 87L68 88L69 88L69 89L70 89L70 90L69 90L69 91L70 91L70 90L71 90L71 89L72 89L72 88L73 88L73 87L70 87L70 86L73 86L73 85L74 85L74 87L75 87L75 86L76 86L76 83L75 83L75 84L74 84L74 83L69 83L69 81L68 81L68 80L69 80L69 79L70 79L70 78L66 78L66 77ZM106 78L106 79L107 79L107 78ZM112 78L112 79L113 79L113 78ZM4 79L4 80L5 80L5 79ZM34 79L34 80L35 80L35 79ZM58 79L58 82L59 82L59 81L60 81L60 80L59 80L59 79ZM67 79L67 80L68 80L68 79ZM87 79L87 80L88 80L88 79ZM9 80L9 81L10 81L10 80ZM38 80L38 81L37 81L37 82L38 82L38 81L39 81L39 80ZM99 80L99 82L98 82L98 84L97 84L97 85L98 85L98 84L100 84L100 83L99 83L99 82L101 82L101 81L100 81L100 80ZM15 81L15 82L17 82L17 81ZM46 81L46 82L47 82L47 81ZM5 83L5 86L8 86L8 83ZM14 83L14 84L15 84L15 83ZM31 83L31 86L34 86L34 83ZM57 83L57 86L60 86L60 83ZM67 83L67 84L68 84L68 83ZM83 83L83 86L86 86L86 83ZM91 83L91 84L93 84L93 83ZM109 83L109 86L112 86L112 83ZM115 83L115 84L117 84L117 83ZM6 84L6 85L7 85L7 84ZM32 84L32 85L33 85L33 84ZM42 84L42 85L44 85L44 84ZM58 84L58 85L59 85L59 84ZM69 84L69 85L71 85L71 84ZM72 84L72 85L73 85L73 84ZM84 84L84 85L85 85L85 84ZM110 84L110 85L111 85L111 84ZM3 86L3 87L4 87L4 86ZM87 86L87 87L86 87L86 88L87 88L87 89L88 89L88 90L89 90L89 91L91 91L91 90L89 90L89 89L88 89L88 86ZM69 87L69 88L70 88L70 87ZM100 87L100 89L99 89L99 90L100 90L100 89L101 89L101 88L102 88L102 87ZM27 88L27 89L28 89L28 88ZM35 89L35 90L36 90L36 89ZM47 89L47 90L50 90L50 89ZM104 89L104 90L105 90L105 89ZM19 90L19 91L18 91L18 92L20 92L20 93L18 93L18 95L17 95L17 94L16 94L16 96L15 96L15 97L14 97L14 99L13 99L13 100L14 100L14 99L15 99L15 97L18 97L18 98L17 98L17 100L18 100L18 99L19 99L19 97L20 97L20 96L19 96L19 94L22 94L22 99L21 99L21 98L20 98L20 100L19 100L19 101L18 101L18 102L14 102L14 104L13 104L13 105L14 105L14 106L16 106L16 104L15 104L15 103L20 103L20 102L21 102L21 103L22 103L22 99L26 99L26 98L27 98L27 95L26 95L26 94L25 94L25 95L24 95L24 93L23 93L23 92L22 92L22 93L21 93L21 92L20 92L20 90ZM75 90L75 91L76 91L76 92L75 92L75 93L76 93L76 92L78 92L78 91L76 91L76 90ZM25 91L25 92L26 92L26 91ZM33 91L33 92L34 92L34 91ZM3 92L3 93L4 93L4 92ZM60 92L60 94L61 94L61 93L62 93L62 92ZM116 92L116 93L117 93L117 92ZM22 93L22 94L23 94L23 93ZM71 93L71 94L72 94L72 93ZM84 93L84 94L85 94L85 93ZM86 93L86 94L87 94L87 95L88 95L88 93ZM101 93L101 95L103 95L103 94L102 94L102 93ZM23 95L23 97L24 97L24 98L25 98L25 97L26 97L26 95L25 95L25 96L24 96L24 95ZM78 95L78 96L80 96L80 95ZM95 95L95 96L96 96L96 98L97 98L97 97L98 97L98 99L99 99L99 97L98 97L98 95ZM7 96L7 97L6 97L6 98L7 98L7 97L8 97L8 96ZM9 96L9 97L10 97L10 96ZM55 96L55 98L56 98L56 96ZM62 96L62 97L63 97L63 96ZM73 96L73 97L74 97L74 96ZM84 96L84 98L85 98L85 97L86 97L86 100L87 100L87 99L88 99L88 98L89 98L89 99L90 99L90 98L89 98L89 97L88 97L88 96L87 96L87 97L86 97L86 96ZM49 97L49 98L50 98L50 97ZM70 97L70 98L71 98L71 97ZM87 97L87 98L88 98L88 97ZM116 97L116 98L115 98L115 100L114 100L114 101L113 101L113 103L116 103L116 104L114 104L114 105L116 105L116 104L117 104L117 103L116 103L116 102L115 102L115 100L116 100L116 101L117 101L117 100L116 100L116 98L117 98L117 97ZM73 98L73 99L74 99L74 98ZM47 99L47 100L45 100L45 103L46 103L46 102L48 102L48 103L47 103L47 105L46 105L46 106L47 106L47 105L49 105L49 104L50 104L50 103L51 103L51 101L49 101L49 99ZM57 99L57 100L56 100L56 101L57 101L57 100L58 100L58 99ZM78 99L78 101L79 101L79 99ZM20 100L20 101L21 101L21 100ZM29 100L29 101L28 101L28 102L27 102L27 101L26 101L26 103L28 103L28 102L29 102L29 101L30 101L30 102L31 102L31 103L33 103L33 101L32 101L32 102L31 102L31 100ZM47 100L47 101L48 101L48 102L49 102L49 103L50 103L50 102L49 102L49 101L48 101L48 100ZM53 100L53 101L52 101L52 103L55 103L55 101L54 101L54 100ZM75 100L75 101L76 101L76 100ZM110 100L110 101L111 101L111 102L112 102L112 100ZM7 101L7 102L6 102L6 103L5 103L5 104L6 104L6 105L8 105L8 104L6 104L6 103L7 103L7 102L8 102L8 101ZM9 101L9 103L10 103L10 104L11 104L11 107L9 107L9 109L10 109L10 110L11 110L11 109L12 109L12 108L14 108L14 109L13 109L13 110L14 110L14 109L15 109L15 108L17 108L17 109L18 109L18 108L19 108L19 104L17 104L17 107L15 107L15 108L14 108L14 107L12 107L12 104L11 104L11 103L10 103L10 101ZM11 101L11 102L12 102L12 103L13 103L13 101ZM34 101L34 102L35 102L35 101ZM104 102L104 103L103 103L103 104L104 104L104 103L105 103L105 102ZM86 103L86 104L89 104L89 106L93 106L93 105L90 105L90 103ZM14 104L14 105L15 105L15 104ZM21 104L21 105L22 105L22 106L23 106L23 107L24 107L24 106L23 106L23 104ZM94 104L94 105L95 105L95 104ZM98 104L98 105L99 105L99 104ZM30 105L30 106L31 106L31 107L32 107L32 106L31 106L31 105ZM34 105L34 106L35 106L35 105ZM82 105L82 106L83 106L83 105ZM101 105L101 106L102 106L102 105ZM104 105L104 106L103 106L103 108L102 108L102 107L99 107L99 109L97 109L97 110L100 110L100 108L102 108L102 109L101 109L101 110L102 110L102 109L103 109L103 108L104 108L104 107L105 107L105 105ZM107 105L107 106L108 106L108 105ZM48 106L48 107L49 107L49 106ZM71 106L71 107L70 107L70 109L71 109L71 107L72 107L72 106ZM94 106L94 107L93 107L93 109L90 109L90 110L93 110L93 109L94 109L94 107L95 107L95 106ZM115 106L115 107L116 107L116 106ZM11 107L11 108L12 108L12 107ZM43 107L43 108L44 108L44 107ZM57 107L57 108L58 108L58 107ZM67 107L67 108L68 108L68 107ZM31 109L31 112L34 112L34 109ZM36 109L36 110L37 110L37 111L38 111L38 110L37 110L37 109ZM57 109L57 112L60 112L60 109ZM83 109L83 112L86 112L86 109ZM109 109L109 112L112 112L112 109ZM29 110L29 111L30 111L30 110ZM32 110L32 111L33 111L33 110ZM51 110L51 111L52 111L52 110ZM58 110L58 111L59 111L59 110ZM84 110L84 111L85 111L85 110ZM110 110L110 111L111 111L111 110ZM40 111L40 114L43 114L43 113L41 113L41 111ZM64 111L64 112L66 112L66 111ZM79 111L79 112L80 112L80 111ZM103 111L103 112L104 112L104 111ZM23 112L23 113L24 113L24 112ZM56 113L56 114L57 114L57 113ZM59 113L59 114L60 114L60 113ZM85 113L85 115L87 115L87 116L88 116L88 114L86 114L86 113ZM65 114L65 115L67 115L67 116L69 116L69 115L70 115L70 114L69 114L69 115L67 115L67 114ZM11 115L11 116L12 116L12 117L14 117L14 116L12 116L12 115ZM29 115L29 116L30 116L30 115ZM75 115L75 116L76 116L76 115ZM78 115L78 117L79 117L79 116L80 116L80 117L81 117L81 116L80 116L80 115ZM99 115L99 116L100 116L100 115ZM23 116L23 117L24 117L24 116ZM35 116L35 117L36 117L36 116ZM46 116L46 117L49 117L49 116ZM114 116L114 117L115 117L115 116ZM116 116L116 117L117 117L117 116ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM117 0L110 0L110 7L117 7ZM116 1L111 1L111 6L116 6ZM115 2L112 2L112 5L115 5ZM0 117L7 117L7 110L0 110ZM1 116L6 116L6 111L1 111ZM2 115L5 115L5 112L2 112Z\" fill=\"#000000\"/></g></g></svg>\n",
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"512\" height=\"512\" viewBox=\"0 0 512 512\"><rect x=\"0\" y=\"0\" width=\"512\" height=\"512\" fill=\"#fefefe\"/><g transform=\"scale(3.436)\"><g transform=\"translate(16.000,16.000)\"><path fill-rule=\"evenodd\" d=\"M8 0L8 4L9 4L9 5L8 5L8 7L9 7L9 5L10 5L10 7L11 7L11 9L10 9L10 8L9 8L9 9L8 9L8 11L7 11L7 10L3 10L3 9L2 9L2 8L0 8L0 9L1 9L1 11L0 11L0 12L2 12L2 10L3 10L3 12L4 12L4 14L3 14L3 15L4 15L4 16L2 16L2 14L1 14L1 13L0 13L0 15L1 15L1 16L0 16L0 19L1 19L1 16L2 16L2 18L3 18L3 17L5 17L5 15L7 15L7 16L6 16L6 17L8 17L8 18L9 18L9 17L10 17L10 18L11 18L11 17L12 17L12 16L13 16L13 15L12 15L12 14L14 14L14 10L15 10L15 11L16 11L16 10L15 10L15 9L16 9L16 8L17 8L17 10L18 10L18 8L17 8L17 6L16 6L16 5L14 5L14 3L17 3L17 5L18 5L18 4L19 4L19 3L20 3L20 4L21 4L21 6L20 6L20 5L19 5L19 6L18 6L18 7L19 7L19 8L21 8L21 6L22 6L22 7L23 7L23 8L22 8L22 9L23 9L23 8L24 8L24 10L22 10L22 11L21 11L21 9L19 9L19 10L20 10L20 11L17 11L17 13L16 13L16 14L15 14L15 15L16 15L16 17L15 17L15 18L13 18L13 19L12 19L12 20L10 20L10 19L9 19L9 20L5 20L5 21L4 21L4 22L3 22L3 20L1 20L1 21L0 21L0 25L1 25L1 26L0 26L0 27L2 27L2 29L1 29L1 28L0 28L0 33L1 33L1 32L2 32L2 33L3 33L3 34L4 34L4 35L2 35L2 34L1 34L1 35L2 35L2 36L1 36L1 37L0 37L0 38L1 38L1 37L2 37L2 38L4 38L4 35L8 35L8 36L6 36L6 37L5 37L5 38L6 38L6 39L0 39L0 41L1 41L1 43L2 43L2 45L1 45L1 46L0 46L0 48L1 48L1 49L2 49L2 48L4 48L4 51L2 51L2 50L0 50L0 54L1 54L1 53L2 53L2 56L0 56L0 57L2 57L2 61L0 61L0 62L2 62L2 63L3 63L3 64L1 64L1 63L0 63L0 64L1 64L1 66L0 66L0 69L1 69L1 70L0 70L0 73L1 73L1 74L2 74L2 75L0 75L0 77L1 77L1 78L0 78L0 80L1 80L1 81L0 81L0 82L1 82L1 84L2 84L2 85L4 85L4 86L2 86L2 88L1 88L1 90L0 90L0 91L1 91L1 90L2 90L2 89L3 89L3 88L4 88L4 90L3 90L3 91L2 91L2 92L3 92L3 94L2 94L2 93L1 93L1 92L0 92L0 93L1 93L1 94L2 94L2 95L1 95L1 96L0 96L0 99L2 99L2 100L0 100L0 101L2 101L2 102L1 102L1 103L0 103L0 107L1 107L1 106L2 106L2 108L1 108L1 109L3 109L3 105L8 105L8 106L10 106L10 105L9 105L9 104L8 104L8 102L9 102L9 103L10 103L10 104L11 104L11 102L12 102L12 105L11 105L11 109L10 109L10 107L8 107L8 108L6 108L6 107L7 107L7 106L4 106L4 107L5 107L5 108L6 108L6 109L8 109L8 110L10 110L10 111L8 111L8 113L9 113L9 114L11 114L11 115L15 115L15 116L12 116L12 117L15 117L15 116L16 116L16 117L17 117L17 116L18 116L18 114L16 114L16 113L17 113L17 112L16 112L16 113L15 113L15 111L16 111L16 110L17 110L17 111L18 111L18 112L20 112L20 111L22 111L22 112L21 112L21 113L22 113L22 114L20 114L20 113L19 113L19 114L20 114L20 115L23 115L23 113L22 113L22 112L23 112L23 110L20 110L20 109L19 109L19 107L18 107L18 106L20 106L20 105L22 105L22 104L23 104L23 103L24 103L24 102L25 102L25 103L26 103L26 104L24 104L24 105L25 105L25 107L26 107L26 109L27 109L27 108L28 108L28 109L29 109L29 110L28 110L28 114L30 114L30 113L32 113L32 114L31 114L31 116L30 116L30 117L33 117L33 116L32 116L32 114L33 114L33 113L36 113L36 114L37 114L37 116L36 116L36 117L38 117L38 116L39 116L39 117L40 117L40 116L41 116L41 117L42 117L42 116L41 116L41 115L38 115L38 114L37 114L37 112L35 112L35 111L36 111L36 110L35 110L35 109L37 109L37 108L35 108L35 106L34 106L34 105L33 105L33 106L34 106L34 107L33 107L33 108L30 108L30 107L32 107L32 105L30 105L30 107L26 107L26 106L27 106L27 105L28 105L28 104L30 104L30 103L31 103L31 104L32 104L32 102L33 102L33 104L36 104L36 103L40 103L40 104L38 104L38 105L37 105L37 106L36 106L36 107L38 107L38 109L40 109L40 110L43 110L43 111L39 111L39 112L41 112L41 113L39 113L39 114L44 114L44 115L43 115L43 116L44 116L44 115L45 115L45 114L46 114L46 116L45 116L45 117L48 117L48 116L47 116L47 115L49 115L49 117L52 117L52 116L53 116L53 115L54 115L54 117L56 117L56 115L57 115L57 114L58 114L58 115L59 115L59 114L63 114L63 115L61 115L61 116L60 116L60 117L62 117L62 116L63 116L63 117L64 117L64 116L65 116L65 115L64 115L64 114L63 114L63 112L61 112L61 111L62 111L62 109L63 109L63 110L64 110L64 112L67 112L67 111L68 111L68 110L70 110L70 111L69 111L69 112L68 112L68 113L67 113L67 114L69 114L69 113L70 113L70 112L72 112L72 113L71 113L71 114L70 114L70 115L69 115L69 116L70 116L70 115L72 115L72 114L73 114L73 113L74 113L74 114L75 114L75 115L74 115L74 116L75 116L75 115L76 115L76 117L77 117L77 114L79 114L79 115L78 115L78 117L80 117L80 114L79 114L79 113L77 113L77 112L78 112L78 111L77 111L77 110L79 110L79 108L80 108L80 106L81 106L81 109L80 109L80 111L79 111L79 112L80 112L80 113L82 113L82 115L86 115L86 114L85 114L85 113L88 113L88 114L87 114L87 115L88 115L88 116L89 116L89 117L90 117L90 116L92 116L92 117L93 117L93 116L92 116L92 113L93 113L93 112L95 112L95 111L97 111L97 112L96 112L96 113L95 113L95 114L94 114L94 117L97 117L97 116L99 116L99 117L101 117L101 116L102 116L102 117L103 117L103 116L104 116L104 115L101 115L101 114L105 114L105 113L106 113L106 115L105 115L105 116L106 116L106 115L107 115L107 113L106 113L106 111L107 111L107 110L108 110L108 113L109 113L109 114L110 114L110 113L111 113L111 114L112 114L112 115L113 115L113 110L115 110L115 112L114 112L114 115L115 115L115 114L117 114L117 111L116 111L116 110L117 110L117 108L116 108L116 107L115 107L115 104L117 104L117 103L115 103L115 101L117 101L117 99L116 99L116 97L117 97L117 95L116 95L116 94L117 94L117 92L116 92L116 90L115 90L115 89L116 89L116 86L117 86L117 83L116 83L116 84L115 84L115 85L114 85L114 87L115 87L115 89L114 89L114 88L112 88L112 89L110 89L110 90L112 90L112 92L111 92L111 91L110 91L110 92L109 92L109 90L108 90L108 91L107 91L107 90L106 90L106 88L105 88L105 89L104 89L104 87L103 87L103 88L102 88L102 90L104 90L104 91L102 91L102 92L101 92L101 91L100 91L100 92L98 92L98 89L99 89L99 90L100 90L100 89L101 89L101 87L100 87L100 89L99 89L99 87L97 87L97 86L98 86L98 85L99 85L99 86L100 86L100 85L101 85L101 86L102 86L102 85L103 85L103 86L105 86L105 85L106 85L106 83L108 83L108 86L106 86L106 87L108 87L108 88L111 88L111 87L113 87L113 83L115 83L115 81L114 81L114 82L111 82L111 81L112 81L112 80L110 80L110 82L109 82L109 81L108 81L108 79L110 79L110 78L111 78L111 77L112 77L112 79L113 79L113 78L114 78L114 79L115 79L115 80L117 80L117 79L116 79L116 78L117 78L117 76L116 76L116 75L115 75L115 76L113 76L113 77L112 77L112 74L111 74L111 73L110 73L110 71L113 71L113 69L112 69L112 68L113 68L113 67L114 67L114 66L113 66L113 65L112 65L112 64L113 64L113 61L117 61L117 59L116 59L116 57L117 57L117 55L116 55L116 54L117 54L117 53L116 53L116 52L117 52L117 51L116 51L116 52L115 52L115 53L116 53L116 54L115 54L115 56L116 56L116 57L115 57L115 58L113 58L113 57L114 57L114 55L113 55L113 54L114 54L114 52L113 52L113 51L114 51L114 50L115 50L115 49L116 49L116 47L115 47L115 48L113 48L113 47L111 47L111 48L110 48L110 47L109 47L109 46L108 46L108 47L107 47L107 45L111 45L111 44L110 44L110 42L108 42L108 40L109 40L109 37L114 37L114 38L115 38L115 37L117 37L117 35L115 35L115 37L114 37L114 35L113 35L113 31L114 31L114 30L115 30L115 32L114 32L114 34L115 34L115 33L117 33L117 32L116 32L116 30L115 30L115 29L117 29L117 28L116 28L116 26L117 26L117 24L116 24L116 26L115 26L115 25L113 25L113 26L112 26L112 23L116 23L116 21L117 21L117 20L116 20L116 18L117 18L117 17L116 17L116 16L115 16L115 17L114 17L114 16L113 16L113 17L114 17L114 18L115 18L115 21L114 21L114 22L113 22L113 18L112 18L112 17L111 17L111 16L110 16L110 13L109 13L109 12L110 12L110 11L109 11L109 10L112 10L112 11L111 11L111 15L114 15L114 14L115 14L115 15L116 15L116 14L115 14L115 13L116 13L116 12L117 12L117 11L116 11L116 9L117 9L117 8L113 8L113 9L112 9L112 8L111 8L111 9L109 9L109 6L108 6L108 5L107 5L107 4L106 4L106 3L109 3L109 1L108 1L108 2L106 2L106 1L107 1L107 0L106 0L106 1L104 1L104 2L103 2L103 0L102 0L102 3L101 3L101 1L98 1L98 7L97 7L97 3L96 3L96 4L95 4L95 2L96 2L96 1L97 1L97 0L95 0L95 2L94 2L94 1L93 1L93 0L90 0L90 3L91 3L91 1L93 1L93 2L94 2L94 4L95 4L95 6L94 6L94 7L95 7L95 6L96 6L96 7L97 7L97 9L96 9L96 8L95 8L95 9L94 9L94 8L92 8L92 7L93 7L93 6L92 6L92 5L93 5L93 4L92 4L92 5L90 5L90 8L92 8L92 9L88 9L88 10L87 10L87 5L88 5L88 4L85 4L85 3L87 3L87 1L88 1L88 3L89 3L89 1L88 1L88 0L87 0L87 1L86 1L86 0L84 0L84 1L83 1L83 3L81 3L81 4L79 4L79 5L78 5L78 8L77 8L77 5L76 5L76 4L75 4L75 1L73 1L73 0L71 0L71 1L72 1L72 2L73 2L73 3L69 3L69 4L68 4L68 2L70 2L70 0L68 0L68 2L67 2L67 1L66 1L66 0L63 0L63 1L62 1L62 0L60 0L60 1L59 1L59 0L58 0L58 1L57 1L57 0L56 0L56 1L55 1L55 0L54 0L54 1L53 1L53 0L52 0L52 1L53 1L53 2L52 2L52 3L51 3L51 0L49 0L49 1L50 1L50 2L49 2L49 3L51 3L51 4L52 4L52 8L47 8L47 6L46 6L46 4L48 4L48 1L47 1L47 3L45 3L45 4L44 4L44 3L43 3L43 1L44 1L44 2L46 2L46 0L45 0L45 1L44 1L44 0L41 0L41 1L40 1L40 0L39 0L39 1L38 1L38 2L37 2L37 4L36 4L36 3L34 3L34 4L31 4L31 3L32 3L32 2L34 2L34 1L35 1L35 2L36 2L36 1L37 1L37 0L36 0L36 1L35 1L35 0L30 0L30 7L29 7L29 6L28 6L28 5L29 5L29 1L26 1L26 2L28 2L28 3L26 3L26 7L25 7L25 6L24 6L24 5L25 5L25 2L24 2L24 1L23 1L23 0L22 0L22 1L21 1L21 2L22 2L22 1L23 1L23 3L24 3L24 5L23 5L23 4L21 4L21 3L20 3L20 2L19 2L19 1L18 1L18 0L17 0L17 1L16 1L16 0L15 0L15 1L13 1L13 0L12 0L12 2L9 2L9 0ZM76 0L76 3L77 3L77 4L78 4L78 3L80 3L80 2L79 2L79 0L78 0L78 1L77 1L77 0ZM81 0L81 2L82 2L82 0ZM17 1L17 3L18 3L18 1ZM56 1L56 3L53 3L53 4L54 4L54 5L56 5L56 3L57 3L57 4L58 4L58 3L61 3L61 2L62 2L62 1L61 1L61 2L59 2L59 1L58 1L58 3L57 3L57 1ZM63 1L63 3L62 3L62 4L61 4L61 8L63 8L63 9L64 9L64 8L67 8L67 7L68 7L68 8L69 8L69 9L68 9L68 10L64 10L64 11L63 11L63 10L62 10L62 11L60 11L60 10L61 10L61 9L57 9L57 10L54 10L54 8L55 8L55 9L56 9L56 8L55 8L55 7L56 7L56 6L55 6L55 7L54 7L54 6L53 6L53 7L54 7L54 8L52 8L52 9L49 9L49 10L48 10L48 9L47 9L47 8L46 8L46 9L47 9L47 10L48 10L48 11L47 11L47 12L46 12L46 10L45 10L45 11L44 11L44 9L40 9L40 6L41 6L41 8L42 8L42 5L43 5L43 3L42 3L42 2L41 2L41 4L40 4L40 6L39 6L39 4L38 4L38 5L36 5L36 4L35 4L35 5L36 5L36 6L35 6L35 7L36 7L36 8L35 8L35 10L38 10L38 12L39 12L39 14L38 14L38 15L37 15L37 14L35 14L35 13L34 13L34 14L35 14L35 15L32 15L32 16L31 16L31 14L32 14L32 12L33 12L33 11L34 11L34 10L33 10L33 9L30 9L30 8L28 8L28 9L27 9L27 10L26 10L26 8L27 8L27 7L28 7L28 6L27 6L27 7L26 7L26 8L25 8L25 11L24 11L24 12L23 12L23 11L22 11L22 14L23 14L23 13L24 13L24 14L26 14L26 15L24 15L24 19L25 19L25 18L26 18L26 19L27 19L27 21L26 21L26 20L25 20L25 21L26 21L26 22L24 22L24 20L21 20L21 21L22 21L22 23L21 23L21 22L19 22L19 19L18 19L18 17L17 17L17 18L15 18L15 19L16 19L16 21L15 21L15 20L13 20L13 21L12 21L12 22L11 22L11 23L10 23L10 24L8 24L8 25L6 25L6 24L7 24L7 23L8 23L8 22L10 22L10 21L5 21L5 22L7 22L7 23L3 23L3 26L2 26L2 27L3 27L3 29L4 29L4 25L6 25L6 26L5 26L5 27L6 27L6 28L9 28L9 26L8 26L8 25L10 25L10 28L11 28L11 25L10 25L10 24L12 24L12 25L13 25L13 26L12 26L12 27L14 27L14 25L16 25L16 26L15 26L15 27L16 27L16 31L19 31L19 32L20 32L20 33L21 33L21 32L23 32L23 34L21 34L21 35L23 35L23 36L24 36L24 38L22 38L22 37L21 37L21 36L19 36L19 37L21 37L21 38L20 38L20 40L21 40L21 39L22 39L22 40L23 40L23 39L24 39L24 40L25 40L25 41L21 41L21 42L23 42L23 44L27 44L27 45L25 45L25 46L26 46L26 47L23 47L23 46L24 46L24 45L23 45L23 46L22 46L22 44L21 44L21 43L20 43L20 41L19 41L19 42L17 42L17 44L14 44L14 42L12 42L12 43L11 43L11 44L9 44L9 42L11 42L11 40L10 40L10 39L11 39L11 36L12 36L12 39L13 39L13 40L12 40L12 41L15 41L15 39L17 39L17 40L16 40L16 41L18 41L18 39L19 39L19 38L18 38L18 37L17 37L17 38L16 38L16 36L17 36L17 35L18 35L18 34L17 34L17 35L15 35L15 36L13 36L13 35L14 35L14 34L13 34L13 32L14 32L14 33L16 33L16 32L14 32L14 30L13 30L13 28L12 28L12 30L13 30L13 32L12 32L12 31L10 31L10 30L11 30L11 29L10 29L10 30L9 30L9 29L8 29L8 30L9 30L9 33L10 33L10 34L9 34L9 35L10 35L10 34L12 34L12 35L11 35L11 36L10 36L10 39L8 39L8 40L9 40L9 42L8 42L8 41L6 41L6 40L7 40L7 39L6 39L6 40L4 40L4 41L5 41L5 42L8 42L8 44L9 44L9 45L8 45L8 46L9 46L9 48L10 48L10 46L9 46L9 45L11 45L11 44L12 44L12 46L11 46L11 49L9 49L9 50L8 50L8 47L6 47L6 46L7 46L7 45L6 45L6 46L5 46L5 45L3 45L3 46L2 46L2 47L1 47L1 48L2 48L2 47L6 47L6 48L7 48L7 49L6 49L6 50L8 50L8 51L6 51L6 52L5 52L5 51L4 51L4 53L3 53L3 52L2 52L2 53L3 53L3 56L8 56L8 55L11 55L11 56L12 56L12 57L10 57L10 56L9 56L9 57L10 57L10 58L9 58L9 60L11 60L11 59L10 59L10 58L12 58L12 57L16 57L16 58L15 58L15 61L14 61L14 58L13 58L13 59L12 59L12 60L13 60L13 62L11 62L11 61L10 61L10 63L9 63L9 61L6 61L6 62L8 62L8 63L4 63L4 62L5 62L5 61L4 61L4 60L3 60L3 61L2 61L2 62L3 62L3 63L4 63L4 64L3 64L3 65L5 65L5 66L4 66L4 67L3 67L3 68L4 68L4 69L3 69L3 70L1 70L1 71L2 71L2 72L9 72L9 73L11 73L11 75L10 75L10 74L9 74L9 75L8 75L8 73L6 73L6 74L5 74L5 75L3 75L3 76L5 76L5 75L6 75L6 76L7 76L7 77L5 77L5 78L7 78L7 77L10 77L10 78L12 78L12 80L11 80L11 81L10 81L10 80L8 80L8 79L9 79L9 78L8 78L8 79L6 79L6 80L5 80L5 81L6 81L6 82L8 82L8 81L9 81L9 82L11 82L11 81L12 81L12 82L13 82L13 81L14 81L14 79L13 79L13 78L12 78L12 77L14 77L14 76L15 76L15 77L16 77L16 80L15 80L15 82L14 82L14 83L11 83L11 84L12 84L12 85L9 85L9 87L6 87L6 88L5 88L5 90L7 90L7 89L8 89L8 90L10 90L10 92L11 92L11 90L10 90L10 89L9 89L9 87L10 87L10 88L12 88L12 89L13 89L13 90L14 90L14 89L15 89L15 90L16 90L16 92L14 92L14 91L12 91L12 93L11 93L11 94L10 94L10 93L9 93L9 94L8 94L8 92L9 92L9 91L8 91L8 92L7 92L7 91L4 91L4 94L5 94L5 93L6 93L6 94L8 94L8 96L7 96L7 95L4 95L4 97L3 97L3 95L2 95L2 96L1 96L1 98L2 98L2 99L4 99L4 102L3 102L3 103L1 103L1 104L4 104L4 102L8 102L8 101L10 101L10 102L11 102L11 100L13 100L13 104L14 104L14 105L12 105L12 106L14 106L14 107L12 107L12 109L14 109L14 111L11 111L11 112L10 112L10 113L11 113L11 114L12 114L12 112L14 112L14 111L15 111L15 109L16 109L16 108L17 108L17 109L18 109L18 108L17 108L17 107L16 107L16 106L17 106L17 105L20 105L20 104L17 104L17 105L16 105L16 104L14 104L14 102L15 102L15 103L17 103L17 102L18 102L18 101L20 101L20 99L19 99L19 100L18 100L18 101L17 101L17 102L16 102L16 99L18 99L18 98L20 98L20 96L21 96L21 95L20 95L20 94L21 94L21 93L20 93L20 94L19 94L19 92L18 92L18 90L16 90L16 88L15 88L15 87L14 87L14 86L16 86L16 87L17 87L17 88L18 88L18 89L19 89L19 88L20 88L20 90L19 90L19 91L21 91L21 89L22 89L22 91L23 91L23 92L24 92L24 93L22 93L22 94L24 94L24 95L22 95L22 96L23 96L23 97L22 97L22 98L21 98L21 99L23 99L23 100L22 100L22 101L25 101L25 102L26 102L26 103L28 103L28 101L27 101L27 102L26 102L26 101L25 101L25 96L24 96L24 95L27 95L27 96L28 96L28 97L27 97L27 98L26 98L26 100L27 100L27 99L30 99L30 98L32 98L32 99L31 99L31 101L29 101L29 103L30 103L30 102L31 102L31 101L32 101L32 100L33 100L33 101L34 101L34 103L36 103L36 101L38 101L38 102L40 102L40 103L41 103L41 106L40 106L40 107L39 107L39 105L38 105L38 107L39 107L39 108L40 108L40 107L42 107L42 108L43 108L43 109L44 109L44 108L45 108L45 109L46 109L46 110L47 110L47 111L46 111L46 113L47 113L47 114L49 114L49 113L50 113L50 112L49 112L49 111L51 111L51 112L52 112L52 111L53 111L53 112L54 112L54 115L56 115L56 114L57 114L57 113L56 113L56 114L55 114L55 112L56 112L56 108L57 108L57 105L56 105L56 103L58 103L58 108L61 108L61 109L62 109L62 107L63 107L63 108L64 108L64 109L65 109L65 111L67 111L67 109L69 109L69 108L73 108L73 109L72 109L72 110L71 110L71 111L72 111L72 112L73 112L73 111L72 111L72 110L74 110L74 111L75 111L75 114L76 114L76 111L75 111L75 110L77 110L77 109L78 109L78 108L79 108L79 106L77 106L77 105L78 105L78 104L79 104L79 102L78 102L78 100L81 100L81 102L82 102L82 104L84 104L84 106L83 106L83 105L81 105L81 103L80 103L80 105L81 105L81 106L82 106L82 107L85 107L85 105L87 105L87 107L86 107L86 108L88 108L88 105L89 105L89 109L90 109L90 111L89 111L89 110L88 110L88 109L87 109L87 111L89 111L89 112L88 112L88 113L89 113L89 115L91 115L91 114L90 114L90 113L89 113L89 112L90 112L90 111L91 111L91 109L92 109L92 110L93 110L93 111L92 111L92 112L91 112L91 113L92 113L92 112L93 112L93 111L94 111L94 110L98 110L98 111L102 111L102 112L101 112L101 113L100 113L100 112L98 112L98 113L97 113L97 114L96 114L96 115L95 115L95 116L97 116L97 114L98 114L98 115L99 115L99 116L100 116L100 115L99 115L99 114L98 114L98 113L100 113L100 114L101 114L101 113L102 113L102 112L103 112L103 113L105 113L105 111L106 111L106 108L107 108L107 109L108 109L108 108L107 108L107 107L111 107L111 106L112 106L112 108L114 108L114 109L115 109L115 107L114 107L114 105L111 105L111 104L115 104L115 103L114 103L114 102L113 102L113 101L115 101L115 95L113 95L113 94L114 94L114 93L112 93L112 94L110 94L110 93L111 93L111 92L110 92L110 93L109 93L109 92L107 92L107 91L104 91L104 93L106 93L106 94L105 94L105 95L103 95L103 92L102 92L102 94L101 94L101 96L102 96L102 99L101 99L101 97L100 97L100 95L99 95L99 94L97 94L97 95L96 95L96 94L95 94L95 93L94 93L94 94L93 94L93 93L92 93L92 92L97 92L97 93L98 93L98 92L97 92L97 87L96 87L96 86L97 86L97 85L98 85L98 83L99 83L99 84L100 84L100 83L102 83L102 84L103 84L103 83L104 83L104 84L105 84L105 83L106 83L106 82L108 82L108 81L107 81L107 79L106 79L106 78L107 78L107 77L109 77L109 78L110 78L110 77L109 77L109 76L110 76L110 73L109 73L109 74L107 74L107 73L108 73L108 72L109 72L109 71L110 71L110 69L109 69L109 68L111 68L111 67L109 67L109 66L110 66L110 65L109 65L109 64L110 64L110 61L109 61L109 64L108 64L108 63L107 63L107 64L106 64L106 62L107 62L107 61L106 61L106 60L107 60L107 59L108 59L108 56L107 56L107 55L104 55L104 58L103 58L103 57L102 57L102 56L100 56L100 54L99 54L99 53L96 53L96 54L95 54L95 52L97 52L97 51L96 51L96 50L95 50L95 52L93 52L93 51L94 51L94 50L93 50L93 47L94 47L94 48L95 48L95 47L96 47L96 48L97 48L97 50L98 50L98 51L101 51L101 50L98 50L98 48L97 48L97 47L99 47L99 49L101 49L101 48L103 48L103 50L102 50L102 51L103 51L103 52L104 52L104 54L105 54L105 53L106 53L106 54L109 54L109 56L112 56L112 55L111 55L111 54L109 54L109 53L110 53L110 52L112 52L112 54L113 54L113 52L112 52L112 51L110 51L110 50L113 50L113 48L111 48L111 49L110 49L110 48L109 48L109 47L108 47L108 48L107 48L107 47L104 47L104 48L103 48L103 46L105 46L105 45L104 45L104 44L106 44L106 45L107 45L107 43L105 43L105 41L103 41L103 39L104 39L104 40L106 40L106 41L107 41L107 39L104 39L104 36L105 36L105 38L106 38L106 36L107 36L107 38L108 38L108 37L109 37L109 36L110 36L110 35L108 35L108 32L107 32L107 31L108 31L108 29L107 29L107 28L105 28L105 27L106 27L106 22L105 22L105 20L106 20L106 21L107 21L107 20L106 20L106 18L107 18L107 17L108 17L108 15L109 15L109 13L108 13L108 11L106 11L106 12L105 12L105 11L104 11L104 13L108 13L108 15L107 15L107 17L106 17L106 16L105 16L105 15L106 15L106 14L104 14L104 16L103 16L103 13L102 13L102 12L103 12L103 10L102 10L102 9L101 9L101 10L99 10L99 9L98 9L98 10L97 10L97 11L95 11L95 12L93 12L93 11L94 11L94 9L93 9L93 10L88 10L88 11L87 11L87 10L86 10L86 9L84 9L84 11L83 11L83 12L82 12L82 11L79 11L79 10L80 10L80 9L81 9L81 10L83 10L83 9L81 9L81 7L82 7L82 6L81 6L81 5L82 5L82 4L81 4L81 5L79 5L79 8L80 8L80 9L78 9L78 10L77 10L77 8L76 8L76 5L75 5L75 8L74 8L74 5L73 5L73 4L74 4L74 3L73 3L73 4L72 4L72 5L73 5L73 7L72 7L72 6L71 6L71 5L68 5L68 4L67 4L67 3L66 3L66 2L65 2L65 4L66 4L66 5L67 5L67 7L66 7L66 6L65 6L65 5L64 5L64 4L63 4L63 3L64 3L64 1ZM84 1L84 3L85 3L85 1ZM38 2L38 3L40 3L40 2ZM77 2L77 3L78 3L78 2ZM104 2L104 3L102 3L102 4L101 4L101 3L99 3L99 4L100 4L100 5L99 5L99 7L98 7L98 8L100 8L100 5L103 5L103 4L104 4L104 5L106 5L106 4L105 4L105 2ZM12 3L12 4L13 4L13 3ZM10 4L10 5L11 5L11 4ZM27 4L27 5L28 5L28 4ZM41 4L41 5L42 5L42 4ZM22 5L22 6L23 6L23 7L24 7L24 6L23 6L23 5ZM31 5L31 8L34 8L34 5ZM50 5L50 7L51 7L51 5ZM57 5L57 8L60 8L60 5ZM62 5L62 7L63 7L63 5ZM83 5L83 8L86 8L86 5ZM11 6L11 7L12 7L12 6ZM13 6L13 8L12 8L12 9L11 9L11 10L10 10L10 9L9 9L9 11L8 11L8 12L7 12L7 11L6 11L6 12L5 12L5 11L4 11L4 12L5 12L5 13L6 13L6 14L8 14L8 15L10 15L10 17L11 17L11 16L12 16L12 15L11 15L11 13L13 13L13 9L14 9L14 8L15 8L15 7L16 7L16 6L15 6L15 7L14 7L14 6ZM19 6L19 7L20 7L20 6ZM32 6L32 7L33 7L33 6ZM36 6L36 7L37 7L37 6ZM38 6L38 7L39 7L39 6ZM43 6L43 7L44 7L44 8L45 8L45 7L46 7L46 6L45 6L45 7L44 7L44 6ZM48 6L48 7L49 7L49 6ZM58 6L58 7L59 7L59 6ZM64 6L64 7L65 7L65 6ZM68 6L68 7L69 7L69 6ZM70 6L70 7L71 7L71 6ZM80 6L80 7L81 7L81 6ZM84 6L84 7L85 7L85 6ZM88 6L88 8L89 8L89 6ZM91 6L91 7L92 7L92 6ZM101 6L101 7L102 7L102 6ZM103 6L103 7L104 7L104 9L105 9L105 8L106 8L106 6L105 6L105 7L104 7L104 6ZM107 6L107 7L108 7L108 6ZM4 8L4 9L7 9L7 8ZM36 8L36 9L39 9L39 8ZM75 8L75 9L74 9L74 10L72 10L72 9L71 9L71 10L70 10L70 11L69 11L69 10L68 10L68 11L67 11L67 12L66 12L66 11L64 11L64 12L60 12L60 11L59 11L59 10L58 10L58 11L57 11L57 13L56 13L56 12L55 12L55 11L52 11L52 12L50 12L50 13L49 13L49 14L48 14L48 13L46 13L46 12L45 12L45 13L44 13L44 14L45 14L45 15L49 15L49 16L47 16L47 18L46 18L46 17L45 17L45 16L44 16L44 15L42 15L42 16L41 16L41 15L40 15L40 14L41 14L41 13L42 13L42 14L43 14L43 13L42 13L42 11L39 11L39 12L41 12L41 13L40 13L40 14L39 14L39 15L40 15L40 17L39 17L39 16L38 16L38 17L37 17L37 19L38 19L38 20L37 20L37 21L35 21L35 19L36 19L36 18L34 18L34 17L31 17L31 16L30 16L30 15L28 15L28 14L30 14L30 13L31 13L31 12L29 12L29 11L28 11L28 12L29 12L29 13L26 13L26 14L27 14L27 16L29 16L29 17L28 17L28 18L27 18L27 17L26 17L26 18L27 18L27 19L28 19L28 22L29 22L29 23L25 23L25 24L23 24L23 25L22 25L22 26L21 26L21 27L19 27L19 28L17 28L17 29L18 29L18 30L19 30L19 29L20 29L20 30L21 30L21 31L23 31L23 29L24 29L24 28L25 28L25 30L24 30L24 32L27 32L27 33L26 33L26 34L25 34L25 33L24 33L24 34L23 34L23 35L24 35L24 34L25 34L25 35L26 35L26 36L25 36L25 37L26 37L26 38L25 38L25 39L26 39L26 38L27 38L27 39L28 39L28 40L29 40L29 41L30 41L30 42L29 42L29 43L27 43L27 42L28 42L28 41L27 41L27 42L26 42L26 41L25 41L25 42L24 42L24 43L27 43L27 44L28 44L28 45L27 45L27 47L28 47L28 48L29 48L29 49L27 49L27 48L26 48L26 50L29 50L29 51L28 51L28 53L29 53L29 54L27 54L27 53L26 53L26 52L27 52L27 51L25 51L25 49L24 49L24 48L23 48L23 49L24 49L24 50L21 50L21 48L20 48L20 47L22 47L22 46L21 46L21 45L20 45L20 43L19 43L19 44L18 44L18 45L19 45L19 46L16 46L16 47L17 47L17 48L16 48L16 49L15 49L15 48L14 48L14 49L13 49L13 52L14 52L14 51L15 51L15 53L16 53L16 54L15 54L15 56L16 56L16 57L17 57L17 59L16 59L16 61L15 61L15 62L13 62L13 64L12 64L12 65L14 65L14 64L15 64L15 63L16 63L16 67L15 67L15 71L14 71L14 67L13 67L13 66L10 66L10 67L11 67L11 68L10 68L10 69L9 69L9 70L8 70L8 68L9 68L9 67L8 67L8 66L9 66L9 64L5 64L5 65L8 65L8 66L6 66L6 67L8 67L8 68L5 68L5 67L4 67L4 68L5 68L5 69L4 69L4 71L5 71L5 69L7 69L7 70L6 70L6 71L7 71L7 70L8 70L8 71L12 71L12 69L11 69L11 68L13 68L13 73L12 73L12 72L11 72L11 73L12 73L12 74L14 74L14 75L15 75L15 76L17 76L17 75L18 75L18 72L20 72L20 70L21 70L21 71L22 71L22 72L21 72L21 74L22 74L22 77L23 77L23 76L24 76L24 78L25 78L25 79L26 79L26 80L27 80L27 81L26 81L26 82L25 82L25 80L24 80L24 79L23 79L23 78L22 78L22 79L23 79L23 81L24 81L24 83L25 83L25 84L23 84L23 83L22 83L22 80L21 80L21 79L19 79L19 77L21 77L21 75L19 75L19 76L18 76L18 77L17 77L17 80L18 80L18 81L17 81L17 82L19 82L19 83L18 83L18 84L20 84L20 85L22 85L22 88L23 88L23 91L24 91L24 90L25 90L25 89L26 89L26 90L27 90L27 91L28 91L28 94L27 94L27 92L26 92L26 91L25 91L25 92L26 92L26 93L24 93L24 94L27 94L27 95L28 95L28 96L31 96L31 97L33 97L33 99L34 99L34 100L35 100L35 99L38 99L38 98L39 98L39 99L40 99L40 100L41 100L41 101L40 101L40 102L43 102L43 104L42 104L42 105L43 105L43 107L45 107L45 104L46 104L46 107L48 107L48 108L49 108L49 110L48 110L48 109L47 109L47 108L46 108L46 109L47 109L47 110L48 110L48 111L49 111L49 110L51 110L51 109L52 109L52 110L55 110L55 109L52 109L52 108L53 108L53 107L52 107L52 106L53 106L53 105L55 105L55 108L56 108L56 105L55 105L55 104L54 104L54 103L53 103L53 102L54 102L54 100L56 100L56 99L57 99L57 102L58 102L58 103L59 103L59 105L60 105L60 104L61 104L61 103L62 103L62 104L63 104L63 103L65 103L65 102L63 102L63 103L62 103L62 102L61 102L61 101L63 101L63 99L58 99L58 97L62 97L62 98L63 98L63 95L62 95L62 93L61 93L61 92L60 92L60 93L59 93L59 92L58 92L58 93L57 93L57 94L56 94L56 93L53 93L53 94L56 94L56 97L55 97L55 96L53 96L53 95L52 95L52 96L48 96L48 98L46 98L46 97L47 97L47 94L48 94L48 95L50 95L50 94L51 94L51 93L50 93L50 92L51 92L51 91L52 91L52 92L53 92L53 91L54 91L54 92L56 92L56 90L57 90L57 91L60 91L60 90L61 90L61 91L64 91L64 92L65 92L65 94L67 94L67 95L64 95L64 97L65 97L65 98L64 98L64 101L65 101L65 100L66 100L66 99L65 99L65 98L68 98L68 97L69 97L69 98L70 98L70 99L69 99L69 100L68 100L68 99L67 99L67 100L68 100L68 101L67 101L67 102L68 102L68 103L66 103L66 104L64 104L64 105L63 105L63 107L64 107L64 108L65 108L65 109L67 109L67 107L69 107L69 106L67 106L67 107L64 107L64 106L66 106L66 105L67 105L67 104L68 104L68 103L71 103L71 102L70 102L70 100L72 100L72 99L74 99L74 102L73 102L73 101L72 101L72 103L74 103L74 104L73 104L73 105L72 105L72 106L70 106L70 107L73 107L73 108L74 108L74 110L75 110L75 107L76 107L76 108L77 108L77 106L76 106L76 105L75 105L75 103L74 103L74 102L75 102L75 101L77 101L77 100L78 100L78 99L74 99L74 97L75 97L75 98L79 98L79 99L81 99L81 100L82 100L82 101L83 101L83 102L85 102L85 103L84 103L84 104L85 104L85 103L86 103L86 102L87 102L87 101L88 101L88 104L87 104L87 105L88 105L88 104L90 104L90 105L92 105L92 107L90 107L90 108L92 108L92 109L93 109L93 110L94 110L94 109L96 109L96 107L98 107L98 105L99 105L99 106L103 106L103 108L104 108L104 110L105 110L105 108L106 108L106 107L107 107L107 106L106 106L106 105L107 105L107 104L105 104L105 106L103 106L103 105L104 105L104 103L97 103L97 102L100 102L100 100L101 100L101 102L106 102L106 103L107 103L107 101L105 101L105 100L104 100L104 99L107 99L107 100L108 100L108 102L109 102L109 103L108 103L108 104L111 104L111 103L110 103L110 101L111 101L111 102L112 102L112 103L113 103L113 102L112 102L112 97L114 97L114 96L113 96L113 95L112 95L112 96L110 96L110 94L109 94L109 95L108 95L108 93L107 93L107 92L106 92L106 93L107 93L107 94L106 94L106 95L107 95L107 96L108 96L108 97L107 97L107 98L105 98L105 97L106 97L106 96L105 96L105 97L103 97L103 98L104 98L104 99L102 99L102 100L101 100L101 99L100 99L100 98L99 98L99 95L97 95L97 97L96 97L96 98L95 98L95 96L96 96L96 95L95 95L95 94L94 94L94 95L95 95L95 96L94 96L94 99L93 99L93 97L92 97L92 95L93 95L93 94L92 94L92 93L91 93L91 92L92 92L92 91L93 91L93 90L94 90L94 91L95 91L95 89L96 89L96 87L95 87L95 85L94 85L94 83L98 83L98 81L97 81L97 82L95 82L95 81L96 81L96 79L98 79L98 78L101 78L101 79L99 79L99 83L100 83L100 82L104 82L104 83L105 83L105 82L104 82L104 81L106 81L106 80L104 80L104 79L105 79L105 78L106 78L106 77L107 77L107 74L106 74L106 75L105 75L105 74L103 74L103 75L99 75L99 77L97 77L97 76L98 76L98 73L97 73L97 72L95 72L95 71L97 71L97 70L99 70L99 71L98 71L98 72L99 72L99 71L100 71L100 74L102 74L102 73L104 73L104 71L105 71L105 70L103 70L103 72L102 72L102 71L100 71L100 69L101 69L101 70L102 70L102 69L104 69L104 67L105 67L105 68L106 68L106 72L105 72L105 73L106 73L106 72L108 72L108 71L109 71L109 69L108 69L108 68L109 68L109 67L107 67L107 65L108 65L108 66L109 66L109 65L108 65L108 64L107 64L107 65L106 65L106 64L104 64L104 63L105 63L105 62L106 62L106 61L104 61L104 62L103 62L103 64L102 64L102 65L100 65L100 66L99 66L99 65L98 65L98 64L97 64L97 66L96 66L96 67L98 67L98 66L99 66L99 67L100 67L100 68L97 68L97 70L94 70L94 69L93 69L93 70L94 70L94 71L93 71L93 72L92 72L92 71L88 71L88 70L87 70L87 68L86 68L86 66L87 66L87 67L88 67L88 69L89 69L89 70L91 70L91 68L92 68L92 67L93 67L93 66L92 66L92 65L93 65L93 64L94 64L94 65L95 65L95 64L94 64L94 63L98 63L98 61L99 61L99 60L98 60L98 58L99 58L99 59L100 59L100 61L101 61L101 62L102 62L102 61L103 61L103 60L104 60L104 59L102 59L102 58L101 58L101 57L100 57L100 56L99 56L99 57L98 57L98 54L96 54L96 55L95 55L95 54L94 54L94 53L93 53L93 52L92 52L92 46L94 46L94 47L95 47L95 46L94 46L94 45L95 45L95 44L94 44L94 43L95 43L95 42L94 42L94 41L92 41L92 37L93 37L93 38L94 38L94 39L93 39L93 40L95 40L95 37L96 37L96 36L95 36L95 33L97 33L97 34L96 34L96 35L98 35L98 36L97 36L97 37L98 37L98 38L96 38L96 39L97 39L97 40L96 40L96 42L97 42L97 44L96 44L96 47L97 47L97 46L99 46L99 47L100 47L100 48L101 48L101 46L102 46L102 45L103 45L103 44L104 44L104 42L103 42L103 43L102 43L102 39L100 39L100 38L103 38L103 36L104 36L104 34L103 34L103 32L104 32L104 27L105 27L105 25L104 25L104 23L102 23L102 22L101 22L101 21L104 21L104 19L105 19L105 16L104 16L104 19L103 19L103 20L101 20L101 18L100 18L100 17L99 17L99 15L100 15L100 16L101 16L101 14L102 14L102 13L101 13L101 11L97 11L97 12L98 12L98 13L96 13L96 12L95 12L95 13L94 13L94 15L96 15L96 14L97 14L97 15L98 15L98 17L97 17L97 16L95 16L95 17L94 17L94 19L97 19L97 20L95 20L95 21L94 21L94 20L92 20L92 21L91 21L91 19L93 19L93 18L92 18L92 17L93 17L93 16L92 16L92 15L91 15L91 19L90 19L90 18L89 18L89 19L87 19L87 21L86 21L86 18L88 18L88 16L89 16L89 14L88 14L88 13L89 13L89 11L88 11L88 13L87 13L87 15L85 15L85 16L86 16L86 18L85 18L85 17L84 17L84 16L83 16L83 17L81 17L81 16L82 16L82 14L83 14L83 13L84 13L84 14L86 14L86 12L87 12L87 11L86 11L86 10L85 10L85 12L83 12L83 13L82 13L82 12L81 12L81 13L82 13L82 14L79 14L79 16L77 16L77 15L76 15L76 13L77 13L77 12L78 12L78 13L79 13L79 11L77 11L77 10L76 10L76 8ZM52 9L52 10L53 10L53 9ZM95 9L95 10L96 10L96 9ZM107 9L107 10L108 10L108 9ZM113 9L113 14L114 14L114 13L115 13L115 12L116 12L116 11L115 11L115 10L114 10L114 9ZM31 10L31 11L33 11L33 10ZM49 10L49 11L48 11L48 12L49 12L49 11L50 11L50 10ZM74 10L74 11L71 11L71 12L73 12L73 13L72 13L72 15L70 15L70 14L71 14L71 13L70 13L70 14L68 14L68 13L69 13L69 11L68 11L68 13L65 13L65 12L64 12L64 13L62 13L62 14L61 14L61 13L59 13L59 11L58 11L58 14L57 14L57 15L58 15L58 14L59 14L59 15L60 15L60 16L56 16L56 13L54 13L54 12L52 12L52 13L50 13L50 14L49 14L49 15L51 15L51 16L50 16L50 21L49 21L49 19L48 19L48 18L47 18L47 19L46 19L46 18L45 18L45 17L44 17L44 16L42 16L42 17L43 17L43 18L41 18L41 17L40 17L40 18L38 18L38 19L43 19L43 18L44 18L44 20L43 20L43 21L42 21L42 20L39 20L39 21L40 21L40 22L38 22L38 21L37 21L37 22L38 22L38 27L37 27L37 23L36 23L36 22L35 22L35 23L34 23L34 21L33 21L33 20L34 20L34 19L33 19L33 18L31 18L31 17L30 17L30 18L28 18L28 19L29 19L29 20L31 20L31 21L33 21L33 23L32 23L32 26L33 26L33 27L31 27L31 26L30 26L30 28L31 28L31 30L33 30L33 27L34 27L34 29L35 29L35 28L36 28L36 31L35 31L35 32L36 32L36 33L35 33L35 34L36 34L36 35L35 35L35 37L36 37L36 38L38 38L38 39L39 39L39 40L40 40L40 41L41 41L41 44L40 44L40 45L39 45L39 44L38 44L38 45L39 45L39 46L38 46L38 47L36 47L36 46L37 46L37 45L36 45L36 44L37 44L37 42L38 42L38 43L40 43L40 42L39 42L39 41L38 41L38 40L37 40L37 39L36 39L36 41L37 41L37 42L36 42L36 43L35 43L35 44L34 44L34 42L35 42L35 41L32 41L32 39L29 39L29 40L30 40L30 41L31 41L31 42L30 42L30 43L33 43L33 45L32 45L32 46L33 46L33 47L31 47L31 46L30 46L30 44L29 44L29 46L30 46L30 47L29 47L29 48L30 48L30 47L31 47L31 49L30 49L30 51L29 51L29 53L30 53L30 51L31 51L31 53L32 53L32 50L33 50L33 52L34 52L34 54L35 54L35 55L36 55L36 57L35 57L35 59L36 59L36 60L35 60L35 64L34 64L34 61L33 61L33 63L32 63L32 64L33 64L33 65L35 65L35 66L33 66L33 67L35 67L35 68L34 68L34 69L33 69L33 71L32 71L32 68L31 68L31 67L30 67L30 66L29 66L29 65L31 65L31 66L32 66L32 65L31 65L31 63L30 63L30 61L28 61L28 59L30 59L30 58L28 58L28 59L27 59L27 58L26 58L26 60L27 60L27 61L26 61L26 63L25 63L25 64L28 64L28 63L30 63L30 64L29 64L29 65L28 65L28 66L27 66L27 65L26 65L26 68L28 68L28 66L29 66L29 67L30 67L30 68L31 68L31 70L30 70L30 71L29 71L29 72L31 72L31 71L32 71L32 73L31 73L31 74L32 74L32 75L31 75L31 76L30 76L30 74L29 74L29 73L27 73L27 72L28 72L28 70L29 70L29 69L27 69L27 70L26 70L26 69L25 69L25 70L24 70L24 68L25 68L25 65L24 65L24 67L23 67L23 70L24 70L24 71L23 71L23 72L25 72L25 70L26 70L26 71L27 71L27 72L26 72L26 73L25 73L25 74L24 74L24 73L22 73L22 74L24 74L24 76L25 76L25 74L26 74L26 77L27 77L27 78L26 78L26 79L27 79L27 80L28 80L28 79L27 79L27 78L29 78L29 77L30 77L30 79L31 79L31 80L29 80L29 81L31 81L31 82L32 82L32 81L35 81L35 83L36 83L36 84L37 84L37 85L38 85L38 83L39 83L39 85L40 85L40 86L41 86L41 89L40 89L40 87L39 87L39 86L38 86L38 87L39 87L39 88L38 88L38 92L39 92L39 93L38 93L38 95L34 95L34 94L35 94L35 93L34 93L34 94L32 94L32 92L33 92L33 91L35 91L35 92L37 92L37 91L36 91L36 90L37 90L37 89L35 89L35 87L36 87L36 88L37 88L37 87L36 87L36 85L35 85L35 87L32 87L32 91L30 91L30 93L31 93L31 95L32 95L32 96L33 96L33 97L34 97L34 98L35 98L35 96L36 96L36 97L37 97L37 98L38 98L38 97L39 97L39 98L40 98L40 99L42 99L42 100L43 100L43 101L44 101L44 100L45 100L45 99L46 99L46 101L47 101L47 103L46 103L46 102L45 102L45 103L44 103L44 104L43 104L43 105L44 105L44 104L45 104L45 103L46 103L46 104L48 104L48 105L50 105L50 106L48 106L48 107L51 107L51 108L52 108L52 107L51 107L51 105L50 105L50 104L52 104L52 98L53 98L53 99L55 99L55 98L54 98L54 97L53 97L53 96L52 96L52 97L50 97L50 98L48 98L48 99L46 99L46 98L45 98L45 97L46 97L46 94L47 94L47 93L45 93L45 92L48 92L48 93L49 93L49 91L46 91L46 88L45 88L45 87L47 87L47 88L48 88L48 89L47 89L47 90L48 90L48 89L49 89L49 88L50 88L50 89L51 89L51 90L50 90L50 91L51 91L51 90L54 90L54 91L55 91L55 89L58 89L58 90L59 90L59 89L58 89L58 87L57 87L57 88L56 88L56 86L53 86L53 84L55 84L55 85L56 85L56 81L57 81L57 82L58 82L58 81L60 81L60 80L63 80L63 79L66 79L66 80L65 80L65 81L63 81L63 82L62 82L62 81L61 81L61 82L62 82L62 83L61 83L61 85L62 85L62 87L60 87L60 88L61 88L61 89L62 89L62 90L64 90L64 91L65 91L65 92L67 92L67 94L68 94L68 93L70 93L70 94L69 94L69 95L68 95L68 96L69 96L69 95L70 95L70 98L71 98L71 99L72 99L72 98L71 98L71 96L72 96L72 97L73 97L73 96L74 96L74 95L75 95L75 96L76 96L76 94L77 94L77 95L78 95L78 96L77 96L77 97L79 97L79 95L80 95L80 98L81 98L81 93L80 93L80 92L81 92L81 91L82 91L82 92L83 92L83 95L82 95L82 96L85 96L85 97L82 97L82 100L84 100L84 101L85 101L85 102L86 102L86 101L85 101L85 100L84 100L84 99L85 99L85 97L86 97L86 95L85 95L85 94L86 94L86 92L87 92L87 93L88 93L88 94L87 94L87 95L89 95L89 96L87 96L87 99L86 99L86 100L87 100L87 99L89 99L89 100L88 100L88 101L89 101L89 103L92 103L92 104L94 104L94 105L95 105L95 107L96 107L96 105L98 105L98 104L97 104L97 103L96 103L96 102L95 102L95 101L94 101L94 100L98 100L98 99L99 99L99 100L100 100L100 99L99 99L99 98L98 98L98 97L97 97L97 98L98 98L98 99L94 99L94 100L93 100L93 99L92 99L92 97L91 97L91 98L90 98L90 97L89 97L89 96L90 96L90 95L92 95L92 94L90 94L90 95L89 95L89 93L88 93L88 90L89 90L89 89L88 89L88 90L86 90L86 89L87 89L87 88L86 88L86 87L85 87L85 88L83 88L83 89L82 89L82 88L81 88L81 87L80 87L80 86L81 86L81 85L82 85L82 83L81 83L81 80L83 80L83 79L84 79L84 80L85 80L85 82L88 82L88 81L89 81L89 80L88 80L88 78L89 78L89 79L90 79L90 80L91 80L91 78L89 78L89 76L90 76L90 75L91 75L91 74L90 74L90 75L89 75L89 73L87 73L87 74L86 74L86 72L88 72L88 71L86 71L86 68L85 68L85 67L84 67L84 68L85 68L85 71L86 71L86 72L84 72L84 73L85 73L85 74L84 74L84 77L86 77L86 78L85 78L85 79L84 79L84 78L83 78L83 77L82 77L82 76L83 76L83 75L82 75L82 76L81 76L81 73L80 73L80 76L81 76L81 77L79 77L79 76L78 76L78 75L79 75L79 69L80 69L80 68L81 68L81 69L82 69L82 71L83 71L83 69L82 69L82 68L81 68L81 67L83 67L83 65L85 65L85 66L86 66L86 64L87 64L87 63L90 63L90 65L92 65L92 64L93 64L93 63L92 63L92 64L91 64L91 62L89 62L89 61L90 61L90 60L91 60L91 57L87 57L87 58L88 58L88 59L87 59L87 61L85 61L85 62L83 62L83 61L80 61L80 60L81 60L81 59L82 59L82 57L81 57L81 58L80 58L80 60L79 60L79 57L80 57L80 56L81 56L81 55L80 55L80 56L79 56L79 57L78 57L78 55L79 55L79 54L78 54L78 51L79 51L79 50L80 50L80 52L79 52L79 53L82 53L82 51L83 51L83 48L84 48L84 47L85 47L85 46L84 46L84 45L83 45L83 43L84 43L84 44L85 44L85 45L87 45L87 44L88 44L88 43L90 43L90 44L91 44L91 45L89 45L89 48L88 48L88 46L87 46L87 47L86 47L86 48L85 48L85 51L86 51L86 52L85 52L85 53L84 53L84 55L85 55L85 56L88 56L88 55L92 55L92 57L93 57L93 58L92 58L92 60L93 60L93 61L92 61L92 62L96 62L96 61L97 61L97 59L94 59L94 56L95 56L95 58L96 58L96 56L97 56L97 55L96 55L96 56L95 56L95 55L93 55L93 53L92 53L92 54L91 54L91 53L90 53L90 52L91 52L91 49L90 49L90 48L91 48L91 45L94 45L94 44L93 44L93 43L94 43L94 42L92 42L92 41L90 41L90 40L91 40L91 35L92 35L92 36L93 36L93 37L95 37L95 36L94 36L94 34L92 34L92 33L93 33L93 32L94 32L94 33L95 33L95 32L94 32L94 30L95 30L95 31L96 31L96 32L98 32L98 33L99 33L99 32L100 32L100 31L103 31L103 30L102 30L102 29L103 29L103 27L104 27L104 25L103 25L103 24L102 24L102 23L101 23L101 22L100 22L100 23L99 23L99 21L101 21L101 20L100 20L100 19L99 19L99 18L97 18L97 17L96 17L96 18L97 18L97 19L99 19L99 21L97 21L97 22L96 22L96 21L95 21L95 22L96 22L96 23L94 23L94 21L92 21L92 22L89 22L89 21L90 21L90 20L89 20L89 21L87 21L87 22L86 22L86 23L84 23L84 22L85 22L85 18L84 18L84 17L83 17L83 18L82 18L82 19L81 19L81 18L80 18L80 17L78 17L78 18L77 18L77 19L78 19L78 20L76 20L76 19L75 19L75 17L76 17L76 15L75 15L75 13L74 13L74 12L76 12L76 10ZM9 11L9 13L8 13L8 14L9 14L9 13L10 13L10 12L12 12L12 11ZM25 11L25 12L27 12L27 11ZM35 11L35 12L36 12L36 13L37 13L37 11ZM43 11L43 12L44 12L44 11ZM91 11L91 12L90 12L90 13L91 13L91 14L92 14L92 11ZM6 12L6 13L7 13L7 12ZM17 13L17 14L16 14L16 15L17 15L17 14L18 14L18 13ZM20 13L20 15L21 15L21 16L22 16L22 17L23 17L23 16L22 16L22 15L21 15L21 13ZM53 13L53 15L52 15L52 19L51 19L51 21L52 21L52 20L54 20L54 21L53 21L53 23L52 23L52 22L51 22L51 23L52 23L52 24L50 24L50 22L49 22L49 25L48 25L48 26L47 26L47 20L48 20L48 19L47 19L47 20L46 20L46 21L45 21L45 22L44 22L44 23L43 23L43 24L42 24L42 23L41 23L41 22L42 22L42 21L41 21L41 22L40 22L40 24L39 24L39 27L40 27L40 28L39 28L39 29L38 29L38 28L37 28L37 31L38 31L38 30L42 30L42 28L43 28L43 30L45 30L45 29L46 29L46 28L44 28L44 27L49 27L49 28L50 28L50 25L51 25L51 27L52 27L52 28L51 28L51 30L50 30L50 29L49 29L49 30L46 30L46 31L45 31L45 32L44 32L44 31L43 31L43 32L44 32L44 33L43 33L43 34L44 34L44 33L46 33L46 35L47 35L47 36L48 36L48 35L50 35L50 36L49 36L49 37L48 37L48 38L46 38L46 37L45 37L45 36L44 36L44 35L42 35L42 34L41 34L41 33L42 33L42 32L41 32L41 31L39 31L39 32L38 32L38 33L36 33L36 34L37 34L37 35L36 35L36 36L37 36L37 37L39 37L39 38L42 38L42 37L41 37L41 36L44 36L44 37L45 37L45 38L43 38L43 39L40 39L40 40L42 40L42 41L43 41L43 40L44 40L44 42L43 42L43 43L42 43L42 44L41 44L41 45L42 45L42 44L43 44L43 45L44 45L44 46L42 46L42 47L40 47L40 46L39 46L39 47L40 47L40 48L38 48L38 49L36 49L36 47L34 47L34 48L32 48L32 49L33 49L33 50L34 50L34 52L35 52L35 53L36 53L36 55L37 55L37 57L36 57L36 59L37 59L37 60L36 60L36 62L37 62L37 63L38 63L38 64L39 64L39 63L40 63L40 64L42 64L42 63L41 63L41 62L42 62L42 61L43 61L43 62L44 62L44 63L47 63L47 62L46 62L46 61L48 61L48 62L49 62L49 60L50 60L50 61L51 61L51 63L49 63L49 64L46 64L46 67L45 67L45 69L44 69L44 70L43 70L43 71L41 71L41 70L39 70L39 71L37 71L37 72L36 72L36 74L35 74L35 73L34 73L34 72L35 72L35 70L37 70L37 68L35 68L35 69L34 69L34 72L33 72L33 73L32 73L32 74L33 74L33 73L34 73L34 74L35 74L35 75L37 75L37 77L36 77L36 76L34 76L34 75L33 75L33 77L32 77L32 78L31 78L31 79L32 79L32 80L31 80L31 81L32 81L32 80L33 80L33 79L32 79L32 78L33 78L33 77L36 77L36 78L37 78L37 79L38 79L38 78L39 78L39 80L38 80L38 81L39 81L39 83L40 83L40 84L41 84L41 85L42 85L42 86L43 86L43 87L45 87L45 86L44 86L44 85L46 85L46 86L47 86L47 87L48 87L48 83L49 83L49 84L51 84L51 85L50 85L50 86L49 86L49 87L50 87L50 88L51 88L51 87L52 87L52 89L53 89L53 86L52 86L52 84L53 84L53 82L54 82L54 80L55 80L55 81L56 81L56 80L57 80L57 81L58 81L58 80L60 80L60 79L62 79L62 78L63 78L63 77L64 77L64 78L65 78L65 77L64 77L64 76L65 76L65 74L64 74L64 76L63 76L63 75L61 75L61 74L62 74L62 73L67 73L67 74L68 74L68 75L67 75L67 77L66 77L66 78L67 78L67 77L70 77L70 79L69 79L69 78L68 78L68 79L67 79L67 80L66 80L66 81L65 81L65 83L62 83L62 85L63 85L63 87L64 87L64 88L63 88L63 89L64 89L64 90L65 90L65 89L64 89L64 88L65 88L65 87L64 87L64 85L63 85L63 84L65 84L65 86L67 86L67 87L66 87L66 88L67 88L67 87L69 87L69 90L67 90L67 89L66 89L66 90L67 90L67 92L68 92L68 91L69 91L69 90L70 90L70 93L71 93L71 94L70 94L70 95L71 95L71 94L72 94L72 95L74 95L74 94L73 94L73 93L71 93L71 91L72 91L72 90L71 90L71 86L70 86L70 84L71 84L71 82L72 82L72 83L74 83L74 85L76 85L76 86L77 86L77 85L78 85L78 83L77 83L77 82L80 82L80 78L79 78L79 77L78 77L78 76L77 76L77 74L76 74L76 73L78 73L78 71L77 71L77 69L76 69L76 72L75 72L75 74L76 74L76 76L75 76L75 75L73 75L73 74L72 74L72 73L73 73L73 72L72 72L72 70L73 70L73 71L74 71L74 70L75 70L75 69L74 69L74 70L73 70L73 68L74 68L74 66L72 66L72 67L71 67L71 66L70 66L70 63L74 63L74 64L72 64L72 65L75 65L75 66L78 66L78 64L79 64L79 65L80 65L80 67L75 67L75 68L80 68L80 67L81 67L81 65L83 65L83 64L86 64L86 62L85 62L85 63L83 63L83 64L82 64L82 63L81 63L81 65L80 65L80 61L79 61L79 60L77 60L77 59L75 59L75 60L76 60L76 61L79 61L79 63L76 63L76 62L75 62L75 61L74 61L74 56L75 56L75 55L76 55L76 58L78 58L78 57L77 57L77 54L76 54L76 53L77 53L77 51L78 51L78 50L79 50L79 48L80 48L80 50L81 50L81 51L82 51L82 50L81 50L81 49L82 49L82 48L83 48L83 45L82 45L82 42L84 42L84 41L85 41L85 42L86 42L86 43L87 43L87 42L88 42L88 41L89 41L89 40L90 40L90 39L88 39L88 40L87 40L87 42L86 42L86 38L87 38L87 36L86 36L86 35L87 35L87 32L88 32L88 34L90 34L90 33L92 33L92 31L93 31L93 30L94 30L94 29L95 29L95 30L96 30L96 31L98 31L98 32L99 32L99 31L100 31L100 30L99 30L99 29L100 29L100 28L101 28L101 29L102 29L102 27L101 27L101 26L102 26L102 25L101 25L101 23L100 23L100 25L101 25L101 26L100 26L100 28L98 28L98 29L97 29L97 30L96 30L96 27L98 27L98 25L97 25L97 24L99 24L99 23L97 23L97 24L96 24L96 25L97 25L97 26L96 26L96 27L95 27L95 25L94 25L94 23L93 23L93 24L92 24L92 23L91 23L91 24L90 24L90 25L87 25L87 26L91 26L91 27L88 27L88 29L87 29L87 28L85 28L85 29L83 29L83 30L82 30L82 28L83 28L83 27L84 27L84 26L85 26L85 27L86 27L86 24L85 24L85 25L84 25L84 26L83 26L83 25L82 25L82 24L83 24L83 21L82 21L82 20L83 20L83 19L82 19L82 20L80 20L80 18L79 18L79 21L78 21L78 22L79 22L79 21L80 21L80 22L81 22L81 23L82 23L82 24L80 24L80 23L78 23L78 24L77 24L77 22L75 22L75 23L76 23L76 24L75 24L75 27L76 27L76 32L78 32L78 31L77 31L77 30L78 30L78 29L79 29L79 28L80 28L80 27L82 27L82 28L81 28L81 29L80 29L80 30L81 30L81 32L80 32L80 31L79 31L79 35L78 35L78 34L77 34L77 33L74 33L74 34L73 34L73 33L72 33L72 32L71 32L71 33L69 33L69 32L70 32L70 31L71 31L71 29L74 29L74 28L71 28L71 26L72 26L72 27L74 27L74 26L72 26L72 25L71 25L71 26L70 26L70 25L69 25L69 24L71 24L71 23L72 23L72 24L73 24L73 23L74 23L74 22L73 22L73 21L71 21L71 22L70 22L70 21L69 21L69 20L68 20L68 19L65 19L65 18L66 18L66 17L67 17L67 18L69 18L69 19L74 19L74 17L75 17L75 16L73 16L73 18L69 18L69 16L70 16L70 15L69 15L69 16L68 16L68 17L67 17L67 15L68 15L68 14L67 14L67 15L66 15L66 14L65 14L65 13L64 13L64 14L63 14L63 15L61 15L61 14L60 14L60 15L61 15L61 18L58 18L58 17L56 17L56 18L55 18L55 17L53 17L53 15L54 15L54 16L55 16L55 15L54 15L54 13ZM95 13L95 14L96 14L96 13ZM99 13L99 14L98 14L98 15L99 15L99 14L101 14L101 13ZM4 14L4 15L5 15L5 14ZM64 14L64 15L63 15L63 16L62 16L62 19L63 19L63 20L61 20L61 19L59 19L59 20L58 20L58 21L57 21L57 19L58 19L58 18L56 18L56 19L55 19L55 18L53 18L53 19L55 19L55 20L56 20L56 21L55 21L55 22L54 22L54 23L53 23L53 24L52 24L52 27L54 27L54 28L57 28L57 29L55 29L55 30L52 30L52 31L46 31L46 32L49 32L49 34L50 34L50 35L51 35L51 36L50 36L50 37L51 37L51 38L48 38L48 39L47 39L47 40L46 40L46 39L44 39L44 40L46 40L46 42L45 42L45 43L43 43L43 44L44 44L44 45L45 45L45 47L44 47L44 49L43 49L43 50L42 50L42 49L39 49L39 50L38 50L38 52L39 52L39 53L43 53L43 54L38 54L38 53L37 53L37 51L36 51L36 50L35 50L35 48L34 48L34 50L35 50L35 52L36 52L36 53L37 53L37 55L38 55L38 57L37 57L37 58L38 58L38 61L37 61L37 62L38 62L38 63L39 63L39 62L40 62L40 61L39 61L39 60L40 60L40 59L39 59L39 58L41 58L41 59L44 59L44 60L43 60L43 61L44 61L44 60L45 60L45 61L46 61L46 60L47 60L47 59L48 59L48 60L49 60L49 58L50 58L50 60L51 60L51 61L52 61L52 59L51 59L51 57L52 57L52 58L53 58L53 57L54 57L54 56L52 56L52 54L53 54L53 55L55 55L55 56L56 56L56 55L57 55L57 56L58 56L58 55L57 55L57 54L54 54L54 53L53 53L53 52L55 52L55 50L56 50L56 49L57 49L57 50L59 50L59 49L57 49L57 48L58 48L58 47L57 47L57 48L56 48L56 47L55 47L55 46L51 46L51 43L53 43L53 45L55 45L55 44L56 44L56 41L55 41L55 42L54 42L54 41L53 41L53 40L55 40L55 39L56 39L56 38L57 38L57 42L58 42L58 39L59 39L59 40L60 40L60 39L59 39L59 38L62 38L62 39L63 39L63 40L64 40L64 39L65 39L65 38L66 38L66 39L67 39L67 37L69 37L69 39L68 39L68 40L69 40L69 39L70 39L70 42L69 42L69 43L67 43L67 41L66 41L66 40L65 40L65 41L63 41L63 42L62 42L62 41L59 41L59 42L60 42L60 43L59 43L59 44L60 44L60 43L62 43L62 45L60 45L60 46L59 46L59 45L58 45L58 46L59 46L59 47L61 47L61 48L62 48L62 47L63 47L63 49L64 49L64 50L65 50L65 52L64 52L64 51L63 51L63 50L62 50L62 51L61 51L61 52L62 52L62 54L61 54L61 55L59 55L59 56L61 56L61 55L64 55L64 54L65 54L65 56L64 56L64 57L63 57L63 56L62 56L62 57L61 57L61 58L62 58L62 59L61 59L61 60L62 60L62 61L61 61L61 62L60 62L60 63L59 63L59 61L58 61L58 65L56 65L56 64L54 64L54 63L57 63L57 61L56 61L56 57L55 57L55 58L54 58L54 59L53 59L53 60L54 60L54 59L55 59L55 61L54 61L54 62L53 62L53 65L52 65L52 66L53 66L53 67L51 67L51 64L52 64L52 63L51 63L51 64L50 64L50 67L46 67L46 68L47 68L47 70L50 70L50 71L46 71L46 69L45 69L45 70L44 70L44 72L43 72L43 73L42 73L42 72L41 72L41 73L40 73L40 71L39 71L39 73L40 73L40 75L39 75L39 74L38 74L38 75L39 75L39 76L38 76L38 77L39 77L39 76L40 76L40 77L41 77L41 78L40 78L40 79L43 79L43 78L44 78L44 82L41 82L41 84L43 84L43 85L44 85L44 84L47 84L47 83L48 83L48 82L49 82L49 83L51 83L51 84L52 84L52 82L53 82L53 81L52 81L52 82L49 82L49 81L48 81L48 80L49 80L49 79L50 79L50 81L51 81L51 80L52 80L52 79L51 79L51 78L53 78L53 80L54 80L54 79L55 79L55 80L56 80L56 79L58 79L58 77L62 77L62 76L59 76L59 72L58 72L58 74L56 74L56 73L57 73L57 72L56 72L56 73L55 73L55 71L54 71L54 70L56 70L56 71L58 71L58 69L59 69L59 68L60 68L60 69L61 69L61 70L62 70L62 71L61 71L61 72L62 72L62 71L63 71L63 72L64 72L64 71L65 71L65 72L66 72L66 71L67 71L67 69L68 69L68 70L69 70L69 71L68 71L68 73L69 73L69 75L68 75L68 76L69 76L69 75L71 75L71 76L70 76L70 77L71 77L71 79L70 79L70 82L69 82L69 79L68 79L68 82L69 82L69 83L68 83L68 84L67 84L67 82L66 82L66 83L65 83L65 84L66 84L66 85L68 85L68 86L69 86L69 87L70 87L70 86L69 86L69 85L68 85L68 84L70 84L70 82L71 82L71 81L72 81L72 80L76 80L76 82L77 82L77 80L78 80L78 81L79 81L79 80L78 80L78 79L79 79L79 78L78 78L78 79L77 79L77 78L76 78L76 79L75 79L75 78L74 78L74 77L73 77L73 78L74 78L74 79L72 79L72 76L73 76L73 75L71 75L71 74L70 74L70 73L69 73L69 71L70 71L70 70L69 70L69 69L70 69L70 68L71 68L71 69L72 69L72 68L73 68L73 67L72 67L72 68L71 68L71 67L70 67L70 66L69 66L69 64L68 64L68 65L65 65L65 64L67 64L67 63L70 63L70 61L68 61L68 60L73 60L73 59L68 59L68 58L70 58L70 57L72 57L72 56L74 56L74 55L75 55L75 52L76 52L76 51L77 51L77 50L78 50L78 47L80 47L80 48L82 48L82 47L81 47L81 46L82 46L82 45L81 45L81 46L80 46L80 44L81 44L81 43L80 43L80 42L81 42L81 37L78 37L78 38L77 38L77 37L76 37L76 35L77 35L77 34L76 34L76 35L75 35L75 34L74 34L74 36L75 36L75 37L74 37L74 39L75 39L75 37L76 37L76 39L78 39L78 40L79 40L79 39L80 39L80 41L78 41L78 42L77 42L77 41L76 41L76 43L75 43L75 41L74 41L74 40L73 40L73 39L72 39L72 38L71 38L71 39L70 39L70 37L73 37L73 35L72 35L72 36L70 36L70 34L69 34L69 35L68 35L68 33L67 33L67 32L66 32L66 31L68 31L68 32L69 32L69 31L70 31L70 29L71 29L71 28L70 28L70 27L69 27L69 25L68 25L68 29L67 29L67 26L66 26L66 25L65 25L65 24L64 24L64 23L63 23L63 21L64 21L64 22L65 22L65 23L67 23L67 22L68 22L68 24L69 24L69 23L70 23L70 22L68 22L68 21L67 21L67 22L66 22L66 20L65 20L65 21L64 21L64 18L63 18L63 16L64 16L64 17L65 17L65 16L66 16L66 15L65 15L65 14ZM73 14L73 15L74 15L74 14ZM64 15L64 16L65 16L65 15ZM80 15L80 16L81 16L81 15ZM87 15L87 16L88 16L88 15ZM8 16L8 17L9 17L9 16ZM19 16L19 18L21 18L21 19L23 19L23 18L21 18L21 17L20 17L20 16ZM71 16L71 17L72 17L72 16ZM109 16L109 18L108 18L108 20L109 20L109 19L110 19L110 22L109 22L109 21L108 21L108 22L109 22L109 23L107 23L107 24L109 24L109 25L108 25L108 26L107 26L107 27L108 27L108 28L109 28L109 27L110 27L110 28L111 28L111 27L112 27L112 26L111 26L111 27L110 27L110 26L109 26L109 25L111 25L111 19L112 19L112 18L111 18L111 19L110 19L110 16ZM102 17L102 18L103 18L103 17ZM6 18L6 19L7 19L7 18ZM30 18L30 19L31 19L31 20L32 20L32 19L31 19L31 18ZM59 20L59 22L57 22L57 21L56 21L56 22L55 22L55 24L53 24L53 25L54 25L54 26L55 26L55 27L56 27L56 26L58 26L58 27L57 27L57 28L58 28L58 27L60 27L60 28L61 28L61 29L62 29L62 30L60 30L60 29L59 29L59 30L58 30L58 29L57 29L57 30L56 30L56 31L55 31L55 32L54 32L54 31L52 31L52 32L50 32L50 34L52 34L52 36L51 36L51 37L52 37L52 40L51 40L51 39L50 39L50 40L49 40L49 42L48 42L48 40L47 40L47 42L46 42L46 43L47 43L47 42L48 42L48 43L49 43L49 44L50 44L50 40L51 40L51 42L52 42L52 40L53 40L53 38L54 38L54 37L55 37L55 38L56 38L56 37L57 37L57 38L58 38L58 36L59 36L59 37L62 37L62 38L63 38L63 35L62 35L62 34L64 34L64 37L67 37L67 36L68 36L68 35L67 35L67 34L66 34L66 35L65 35L65 34L64 34L64 33L66 33L66 32L65 32L65 31L64 31L64 32L63 32L63 31L62 31L62 30L64 30L64 29L65 29L65 30L66 30L66 27L65 27L65 26L64 26L64 28L63 28L63 27L60 27L60 26L59 26L59 25L61 25L61 26L62 26L62 24L63 24L63 25L64 25L64 24L63 24L63 23L62 23L62 22L61 22L61 21L60 21L60 20ZM75 20L75 21L76 21L76 20ZM13 21L13 22L12 22L12 24L17 24L17 26L18 26L18 25L19 25L19 26L20 26L20 24L21 24L21 23L20 23L20 24L19 24L19 23L15 23L15 22L14 22L14 21ZM29 21L29 22L30 22L30 23L29 23L29 24L27 24L27 25L26 25L26 24L25 24L25 27L27 27L27 28L28 28L28 31L30 31L30 30L29 30L29 28L28 28L28 27L29 27L29 25L30 25L30 24L31 24L31 22L30 22L30 21ZM81 21L81 22L82 22L82 21ZM1 22L1 25L2 25L2 22ZM45 22L45 23L44 23L44 24L43 24L43 25L42 25L42 26L41 26L41 24L40 24L40 26L41 26L41 27L44 27L44 24L45 24L45 26L46 26L46 24L45 24L45 23L46 23L46 22ZM56 22L56 25L55 25L55 26L56 26L56 25L59 25L59 24L62 24L62 23L61 23L61 22L59 22L59 23L57 23L57 22ZM72 22L72 23L73 23L73 22ZM87 22L87 23L88 23L88 24L89 24L89 22ZM35 23L35 24L34 24L34 25L33 25L33 26L34 26L34 27L36 27L36 23ZM27 25L27 27L28 27L28 25ZM76 25L76 26L77 26L77 27L78 27L78 28L77 28L77 29L78 29L78 28L79 28L79 25L78 25L78 26L77 26L77 25ZM91 25L91 26L92 26L92 28L91 28L91 29L94 29L94 26L93 26L93 25ZM6 26L6 27L7 27L7 26ZM23 26L23 27L21 27L21 28L20 28L20 29L21 29L21 30L22 30L22 29L23 29L23 28L24 28L24 26ZM108 26L108 27L109 27L109 26ZM113 26L113 27L114 27L114 29L111 29L111 30L114 30L114 29L115 29L115 27L114 27L114 26ZM14 28L14 29L15 29L15 28ZM40 28L40 29L41 29L41 28ZM47 28L47 29L48 29L48 28ZM52 28L52 29L53 29L53 28ZM69 28L69 29L68 29L68 30L69 30L69 29L70 29L70 28ZM6 29L6 30L7 30L7 29ZM26 29L26 31L27 31L27 29ZM85 29L85 30L86 30L86 29ZM88 29L88 30L87 30L87 31L89 31L89 33L90 33L90 32L91 32L91 30L89 30L89 29ZM105 29L105 30L106 30L106 31L105 31L105 32L106 32L106 31L107 31L107 29ZM2 30L2 31L3 31L3 32L4 32L4 31L3 31L3 30ZM72 30L72 31L73 31L73 32L75 32L75 31L74 31L74 30ZM98 30L98 31L99 31L99 30ZM5 31L5 34L8 34L8 31ZM31 31L31 34L34 34L34 31ZM57 31L57 34L60 34L60 31ZM61 31L61 32L62 32L62 33L63 33L63 32L62 32L62 31ZM83 31L83 34L86 34L86 31ZM109 31L109 34L112 34L112 31ZM6 32L6 33L7 33L7 32ZM10 32L10 33L11 33L11 32ZM17 32L17 33L18 33L18 32ZM28 32L28 33L29 33L29 34L28 34L28 35L27 35L27 34L26 34L26 35L27 35L27 36L26 36L26 37L27 37L27 36L28 36L28 38L30 38L30 37L32 37L32 38L34 38L34 37L33 37L33 36L34 36L34 35L31 35L31 36L30 36L30 37L29 37L29 36L28 36L28 35L29 35L29 34L30 34L30 33L29 33L29 32ZM32 32L32 33L33 33L33 32ZM40 32L40 33L41 33L41 32ZM55 32L55 34L53 34L53 33L52 33L52 34L53 34L53 35L54 35L54 36L55 36L55 34L56 34L56 32ZM58 32L58 33L59 33L59 32ZM81 32L81 33L82 33L82 32ZM84 32L84 33L85 33L85 32ZM101 32L101 33L100 33L100 34L98 34L98 35L99 35L99 36L98 36L98 37L99 37L99 36L100 36L100 37L101 37L101 36L100 36L100 34L101 34L101 35L103 35L103 34L101 34L101 33L102 33L102 32ZM110 32L110 33L111 33L111 32ZM38 33L38 36L39 36L39 37L40 37L40 34L39 34L39 33ZM71 33L71 34L72 34L72 33ZM105 33L105 35L107 35L107 36L108 36L108 35L107 35L107 33ZM19 34L19 35L20 35L20 34ZM47 34L47 35L48 35L48 34ZM80 34L80 36L82 36L82 38L84 38L84 40L85 40L85 37L86 37L86 36L85 36L85 37L83 37L83 36L84 36L84 35L81 35L81 34ZM56 35L56 36L57 36L57 35ZM59 35L59 36L60 36L60 35ZM61 35L61 36L62 36L62 35ZM66 35L66 36L67 36L67 35ZM88 35L88 36L89 36L89 35ZM111 35L111 36L112 36L112 35ZM2 36L2 37L3 37L3 36ZM8 36L8 38L9 38L9 36ZM52 36L52 37L53 37L53 36ZM69 36L69 37L70 37L70 36ZM6 37L6 38L7 38L7 37ZM88 37L88 38L90 38L90 37ZM98 38L98 39L99 39L99 41L98 41L98 40L97 40L97 42L98 42L98 45L99 45L99 46L101 46L101 45L99 45L99 43L101 43L101 44L102 44L102 43L101 43L101 42L99 42L99 41L101 41L101 40L100 40L100 39L99 39L99 38ZM110 38L110 41L111 41L111 40L112 40L112 42L113 42L113 41L115 41L115 40L117 40L117 39L115 39L115 40L113 40L113 39L111 39L111 38ZM33 39L33 40L34 40L34 39ZM71 39L71 41L72 41L72 43L71 43L71 42L70 42L70 43L69 43L69 45L68 45L68 44L67 44L67 45L66 45L66 46L67 46L67 45L68 45L68 47L66 47L66 48L65 48L65 45L63 45L63 47L64 47L64 48L65 48L65 49L66 49L66 50L67 50L67 51L66 51L66 53L67 53L67 51L69 51L69 52L68 52L68 53L69 53L69 54L67 54L67 55L66 55L66 56L67 56L67 57L66 57L66 58L67 58L67 57L70 57L70 56L67 56L67 55L74 55L74 53L73 53L73 52L75 52L75 51L76 51L76 49L75 49L75 47L76 47L76 48L77 48L77 47L78 47L78 46L77 46L77 45L76 45L76 46L75 46L75 43L73 43L73 42L74 42L74 41L72 41L72 39ZM82 39L82 40L83 40L83 39ZM65 41L65 42L64 42L64 44L65 44L65 42L66 42L66 41ZM53 42L53 43L54 43L54 44L55 44L55 43L54 43L54 42ZM91 42L91 44L92 44L92 42ZM115 42L115 43L116 43L116 44L113 44L113 43L112 43L112 46L115 46L115 45L116 45L116 44L117 44L117 43L116 43L116 42ZM5 43L5 44L7 44L7 43ZM57 43L57 44L58 44L58 43ZM70 43L70 45L69 45L69 47L68 47L68 49L70 49L70 50L69 50L69 51L70 51L70 52L69 52L69 53L71 53L71 54L73 54L73 53L71 53L71 52L73 52L73 51L70 51L70 50L72 50L72 48L73 48L73 50L74 50L74 51L75 51L75 50L74 50L74 48L73 48L73 47L72 47L72 46L73 46L73 45L74 45L74 44L73 44L73 43L72 43L72 45L71 45L71 43ZM76 43L76 44L77 44L77 43ZM79 43L79 44L80 44L80 43ZM108 43L108 44L109 44L109 43ZM46 44L46 45L48 45L48 47L49 47L49 49L48 49L48 48L47 48L47 49L46 49L46 50L45 50L45 49L44 49L44 50L45 50L45 51L42 51L42 50L41 50L41 51L40 51L40 50L39 50L39 51L40 51L40 52L41 52L41 51L42 51L42 52L43 52L43 53L44 53L44 52L45 52L45 53L46 53L46 55L45 55L45 54L44 54L44 55L42 55L42 56L41 56L41 55L40 55L40 56L39 56L39 57L38 57L38 58L39 58L39 57L40 57L40 56L41 56L41 57L42 57L42 58L44 58L44 59L45 59L45 58L46 58L46 59L47 59L47 58L46 58L46 55L47 55L47 54L48 54L48 55L50 55L50 54L52 54L52 53L51 53L51 52L50 52L50 50L51 50L51 51L52 51L52 52L53 52L53 51L52 51L52 50L51 50L51 49L50 49L50 46L49 46L49 45L48 45L48 44ZM13 46L13 47L12 47L12 48L13 48L13 47L15 47L15 46ZM19 46L19 47L18 47L18 48L17 48L17 49L16 49L16 50L15 50L15 51L19 51L19 53L18 53L18 52L16 52L16 53L18 53L18 54L19 54L19 55L18 55L18 60L17 60L17 61L16 61L16 62L17 62L17 64L18 64L18 66L17 66L17 67L16 67L16 71L19 71L19 70L18 70L18 69L21 69L21 68L22 68L22 67L21 67L21 65L22 65L22 66L23 66L23 65L22 65L22 62L21 62L21 61L22 61L22 60L23 60L23 64L24 64L24 62L25 62L25 61L24 61L24 58L25 58L25 56L21 56L21 55L22 55L22 54L24 54L24 55L27 55L27 57L28 57L28 56L31 56L31 55L32 55L32 54L30 54L30 55L27 55L27 54L26 54L26 53L22 53L22 52L23 52L23 51L21 51L21 50L19 50L19 49L18 49L18 48L19 48L19 47L20 47L20 46ZM46 46L46 47L45 47L45 48L46 48L46 47L47 47L47 46ZM61 46L61 47L62 47L62 46ZM70 46L70 47L69 47L69 48L70 48L70 49L71 49L71 48L72 48L72 47L71 47L71 46ZM74 46L74 47L75 47L75 46ZM76 46L76 47L77 47L77 46ZM42 47L42 48L43 48L43 47ZM51 47L51 48L55 48L55 49L56 49L56 48L55 48L55 47ZM70 47L70 48L71 48L71 47ZM87 48L87 49L86 49L86 50L88 50L88 48ZM106 48L106 50L105 50L105 49L104 49L104 50L103 50L103 51L107 51L107 53L109 53L109 52L110 52L110 51L109 51L109 49L108 49L108 51L107 51L107 48ZM11 49L11 50L12 50L12 49ZM17 49L17 50L18 50L18 49ZM47 49L47 50L48 50L48 49ZM53 49L53 50L54 50L54 49ZM60 49L60 50L61 50L61 49ZM89 49L89 50L90 50L90 49ZM8 51L8 52L6 52L6 53L4 53L4 55L7 55L7 54L6 54L6 53L9 53L9 54L10 54L10 52L9 52L9 51ZM11 51L11 55L12 55L12 51ZM20 51L20 52L21 52L21 51ZM45 51L45 52L46 52L46 53L47 53L47 52L49 52L49 51ZM57 51L57 52L56 52L56 53L58 53L58 54L60 54L60 53L59 53L59 52L60 52L60 51L59 51L59 52L58 52L58 51ZM62 51L62 52L63 52L63 51ZM88 51L88 52L90 52L90 51ZM108 51L108 52L109 52L109 51ZM86 52L86 53L85 53L85 55L87 55L87 54L86 54L86 53L87 53L87 52ZM20 53L20 54L22 54L22 53ZM48 53L48 54L50 54L50 53ZM89 53L89 54L90 54L90 53ZM101 53L101 55L103 55L103 53ZM16 54L16 56L17 56L17 54ZM82 54L82 56L83 56L83 54ZM13 55L13 56L14 56L14 55ZM33 55L33 56L34 56L34 55ZM44 55L44 56L42 56L42 57L44 57L44 58L45 58L45 57L44 57L44 56L45 56L45 55ZM47 56L47 57L49 57L49 56ZM50 56L50 57L51 57L51 56ZM105 56L105 58L106 58L106 59L105 59L105 60L106 60L106 59L107 59L107 58L106 58L106 57L107 57L107 56ZM5 57L5 60L8 60L8 57ZM19 57L19 58L20 58L20 57ZM21 57L21 58L22 58L22 59L23 59L23 58L24 58L24 57ZM31 57L31 60L34 60L34 57ZM57 57L57 60L60 60L60 57ZM62 57L62 58L63 58L63 57ZM83 57L83 60L86 60L86 57ZM99 57L99 58L100 58L100 57ZM109 57L109 60L112 60L112 57ZM0 58L0 60L1 60L1 58ZM3 58L3 59L4 59L4 58ZM6 58L6 59L7 59L7 58ZM32 58L32 59L33 59L33 58ZM58 58L58 59L59 59L59 58ZM64 58L64 60L63 60L63 59L62 59L62 60L63 60L63 61L62 61L62 62L61 62L61 63L62 63L62 66L61 66L61 65L60 65L60 64L59 64L59 65L58 65L58 66L57 66L57 67L56 67L56 65L54 65L54 66L55 66L55 67L53 67L53 68L52 68L52 69L50 69L50 68L51 68L51 67L50 67L50 68L48 68L48 69L50 69L50 70L51 70L51 71L52 71L52 72L50 72L50 73L47 73L47 72L46 72L46 71L45 71L45 72L44 72L44 73L43 73L43 76L42 76L42 75L40 75L40 76L41 76L41 77L42 77L42 78L43 78L43 76L44 76L44 77L46 77L46 79L48 79L48 78L47 78L47 77L50 77L50 78L51 78L51 77L53 77L53 78L54 78L54 76L55 76L55 79L56 79L56 78L57 78L57 77L58 77L58 75L57 75L57 77L56 77L56 76L55 76L55 75L56 75L56 74L55 74L55 73L54 73L54 71L53 71L53 69L54 69L54 68L55 68L55 69L56 69L56 70L57 70L57 69L58 69L58 68L59 68L59 67L58 67L58 66L61 66L61 68L62 68L62 70L63 70L63 69L64 69L64 68L68 68L68 69L69 69L69 68L68 68L68 67L69 67L69 66L67 66L67 67L65 67L65 66L64 66L64 68L62 68L62 66L63 66L63 65L64 65L64 64L65 64L65 62L66 62L66 61L67 61L67 62L68 62L68 61L67 61L67 60L68 60L68 59L67 59L67 60L65 60L65 58ZM84 58L84 59L85 59L85 58ZM110 58L110 59L111 59L111 58ZM20 59L20 60L18 60L18 61L17 61L17 62L18 62L18 64L19 64L19 65L20 65L20 64L21 64L21 62L18 62L18 61L21 61L21 59ZM88 59L88 60L90 60L90 59ZM113 59L113 60L116 60L116 59ZM41 60L41 61L42 61L42 60ZM95 60L95 61L96 61L96 60ZM3 61L3 62L4 62L4 61ZM27 61L27 63L28 63L28 61ZM71 61L71 62L72 62L72 61ZM62 62L62 63L63 63L63 64L64 64L64 63L63 63L63 62ZM74 62L74 63L75 63L75 65L76 65L76 63L75 63L75 62ZM99 62L99 63L100 63L100 64L101 64L101 63L100 63L100 62ZM115 62L115 63L116 63L116 65L115 65L115 64L114 64L114 65L115 65L115 67L117 67L117 63L116 63L116 62ZM111 63L111 64L112 64L112 63ZM35 64L35 65L37 65L37 64ZM43 64L43 65L42 65L42 66L41 66L41 65L40 65L40 66L41 66L41 67L39 67L39 69L41 69L41 67L42 67L42 68L44 68L44 66L45 66L45 64ZM43 65L43 66L42 66L42 67L43 67L43 66L44 66L44 65ZM47 65L47 66L49 66L49 65ZM88 65L88 66L89 66L89 68L91 68L91 67L92 67L92 66L89 66L89 65ZM103 65L103 66L102 66L102 67L101 67L101 66L100 66L100 67L101 67L101 68L102 68L102 67L104 67L104 65ZM1 66L1 69L2 69L2 66ZM35 66L35 67L38 67L38 66ZM94 66L94 68L95 68L95 69L96 69L96 68L95 68L95 66ZM105 66L105 67L106 67L106 68L107 68L107 67L106 67L106 66ZM112 66L112 67L113 67L113 66ZM17 67L17 68L18 68L18 67ZM19 67L19 68L21 68L21 67ZM55 67L55 68L56 68L56 69L57 69L57 68L58 68L58 67L57 67L57 68L56 68L56 67ZM114 68L114 70L115 70L115 71L116 71L116 73L117 73L117 71L116 71L116 70L117 70L117 68L116 68L116 69L115 69L115 68ZM107 69L107 71L108 71L108 69ZM111 69L111 70L112 70L112 69ZM59 70L59 71L60 71L60 70ZM80 71L80 72L81 72L81 71ZM15 72L15 75L16 75L16 74L17 74L17 72ZM37 72L37 73L38 73L38 72ZM45 72L45 74L44 74L44 76L52 76L52 75L51 75L51 73L50 73L50 75L45 75L45 74L47 74L47 73L46 73L46 72ZM52 72L52 74L53 74L53 76L54 76L54 75L55 75L55 74L54 74L54 73L53 73L53 72ZM94 72L94 74L93 74L93 75L92 75L92 76L91 76L91 77L93 77L93 78L92 78L92 81L93 81L93 82L91 82L91 81L90 81L90 82L89 82L89 83L90 83L90 84L91 84L91 85L93 85L93 87L91 87L91 86L89 86L89 84L88 84L88 83L87 83L87 85L88 85L88 86L87 86L87 87L91 87L91 88L90 88L90 91L89 91L89 92L90 92L90 91L91 91L91 90L92 90L92 89L94 89L94 88L95 88L95 87L94 87L94 85L93 85L93 83L94 83L94 80L95 80L95 79L96 79L96 78L95 78L95 79L94 79L94 76L93 76L93 75L94 75L94 74L96 74L96 75L95 75L95 77L96 77L96 76L97 76L97 73L95 73L95 72ZM101 72L101 73L102 73L102 72ZM113 72L113 73L114 73L114 72ZM2 73L2 74L4 74L4 73ZM26 73L26 74L27 74L27 75L28 75L28 76L27 76L27 77L29 77L29 75L28 75L28 74L27 74L27 73ZM82 73L82 74L83 74L83 73ZM6 74L6 75L7 75L7 76L8 76L8 75L7 75L7 74ZM85 74L85 75L86 75L86 74ZM87 74L87 75L88 75L88 76L89 76L89 75L88 75L88 74ZM9 75L9 76L10 76L10 77L11 77L11 76L12 76L12 75L11 75L11 76L10 76L10 75ZM104 75L104 76L103 76L103 77L102 77L102 76L100 76L100 77L101 77L101 78L102 78L102 79L101 79L101 80L102 80L102 81L103 81L103 80L102 80L102 79L104 79L104 77L106 77L106 76L105 76L105 75ZM86 76L86 77L87 77L87 78L86 78L86 79L87 79L87 78L88 78L88 77L87 77L87 76ZM3 77L3 78L1 78L1 80L2 80L2 81L1 81L1 82L2 82L2 81L3 81L3 83L2 83L2 84L3 84L3 83L4 83L4 81L3 81L3 80L4 80L4 77ZM81 77L81 78L82 78L82 79L83 79L83 78L82 78L82 77ZM114 77L114 78L115 78L115 77ZM34 78L34 79L35 79L35 78ZM59 78L59 79L60 79L60 78ZM2 79L2 80L3 80L3 79ZM18 79L18 80L19 80L19 81L20 81L20 82L21 82L21 80L19 80L19 79ZM71 79L71 80L72 80L72 79ZM76 79L76 80L77 80L77 79ZM6 80L6 81L7 81L7 80ZM12 80L12 81L13 81L13 80ZM35 80L35 81L36 81L36 82L37 82L37 83L38 83L38 82L37 82L37 80ZM39 80L39 81L40 81L40 80ZM42 80L42 81L43 81L43 80ZM46 80L46 81L47 81L47 80ZM86 80L86 81L88 81L88 80ZM73 81L73 82L74 82L74 83L75 83L75 84L76 84L76 85L77 85L77 84L76 84L76 83L75 83L75 82L74 82L74 81ZM82 81L82 82L83 82L83 81ZM26 82L26 83L29 83L29 82ZM44 82L44 83L45 83L45 82ZM46 82L46 83L47 83L47 82ZM5 83L5 86L8 86L8 83ZM9 83L9 84L10 84L10 83ZM14 83L14 85L15 85L15 83ZM20 83L20 84L22 84L22 85L23 85L23 84L22 84L22 83ZM31 83L31 86L34 86L34 83ZM57 83L57 86L60 86L60 83ZM79 83L79 86L80 86L80 83ZM83 83L83 86L86 86L86 83ZM91 83L91 84L92 84L92 83ZM109 83L109 86L112 86L112 83ZM6 84L6 85L7 85L7 84ZM28 84L28 87L27 87L27 85L25 85L25 86L23 86L23 88L24 88L24 89L25 89L25 87L27 87L27 88L28 88L28 87L29 87L29 85L30 85L30 84ZM32 84L32 85L33 85L33 84ZM58 84L58 85L59 85L59 84ZM84 84L84 85L85 85L85 84ZM96 84L96 85L97 85L97 84ZM110 84L110 85L111 85L111 84ZM0 85L0 86L1 86L1 85ZM16 85L16 86L17 86L17 85ZM18 85L18 87L19 87L19 85ZM10 86L10 87L11 87L11 86ZM50 86L50 87L51 87L51 86ZM73 86L73 87L74 87L74 90L73 90L73 92L74 92L74 93L76 93L76 92L78 92L78 94L80 94L80 93L79 93L79 92L78 92L78 91L77 91L77 88L78 88L78 89L79 89L79 91L81 91L81 89L79 89L79 88L80 88L80 87L76 87L76 92L75 92L75 91L74 91L74 90L75 90L75 87L74 87L74 86ZM12 87L12 88L14 88L14 87ZM54 87L54 89L55 89L55 87ZM93 87L93 88L94 88L94 87ZM6 88L6 89L7 89L7 88ZM30 88L30 89L28 89L28 90L31 90L31 88ZM42 88L42 89L45 89L45 88ZM33 89L33 90L34 90L34 89ZM112 89L112 90L113 90L113 92L115 92L115 90L113 90L113 89ZM39 90L39 91L41 91L41 92L40 92L40 93L39 93L39 94L40 94L40 97L43 97L43 99L44 99L44 96L41 96L41 95L42 95L42 94L43 94L43 95L45 95L45 93L43 93L43 91L45 91L45 90L43 90L43 91L41 91L41 90ZM83 91L83 92L86 92L86 91ZM6 92L6 93L7 93L7 92ZM16 92L16 93L13 93L13 94L11 94L11 96L10 96L10 95L9 95L9 97L10 97L10 98L11 98L11 96L12 96L12 97L13 97L13 99L14 99L14 97L16 97L16 98L17 98L17 96L18 96L18 95L17 95L17 94L18 94L18 92ZM41 92L41 93L42 93L42 92ZM36 93L36 94L37 94L37 93ZM60 93L60 94L59 94L59 95L57 95L57 96L60 96L60 95L61 95L61 93ZM63 93L63 94L64 94L64 93ZM14 94L14 95L12 95L12 96L13 96L13 97L14 97L14 96L15 96L15 94ZM28 94L28 95L29 95L29 94ZM16 95L16 96L17 96L17 95ZM19 95L19 96L20 96L20 95ZM33 95L33 96L34 96L34 95ZM38 95L38 96L37 96L37 97L38 97L38 96L39 96L39 95ZM5 96L5 97L4 97L4 98L5 98L5 99L7 99L7 100L6 100L6 101L8 101L8 98L5 98L5 97L7 97L7 96ZM65 96L65 97L66 97L66 96ZM23 97L23 98L24 98L24 97ZM29 97L29 98L30 98L30 97ZM56 97L56 98L57 98L57 97ZM88 97L88 98L89 98L89 97ZM109 97L109 98L107 98L107 99L109 99L109 98L110 98L110 99L111 99L111 98L110 98L110 97ZM10 99L10 100L11 100L11 99ZM49 99L49 100L51 100L51 99ZM91 99L91 101L90 101L90 102L91 102L91 101L92 101L92 102L93 102L93 101L92 101L92 99ZM14 100L14 101L15 101L15 100ZM58 100L58 102L59 102L59 103L61 103L61 102L60 102L60 100ZM103 100L103 101L104 101L104 100ZM109 100L109 101L110 101L110 100ZM48 101L48 102L49 102L49 104L50 104L50 101ZM55 101L55 102L56 102L56 101ZM20 102L20 103L21 103L21 102ZM22 102L22 103L23 103L23 102ZM76 102L76 104L78 104L78 103L77 103L77 102ZM94 102L94 103L95 103L95 102ZM5 103L5 104L7 104L7 103ZM26 104L26 105L27 105L27 104ZM70 104L70 105L71 105L71 104ZM100 104L100 105L101 105L101 104ZM102 104L102 105L103 105L103 104ZM15 105L15 106L16 106L16 105ZM109 105L109 106L111 106L111 105ZM23 106L23 107L24 107L24 106ZM61 106L61 107L62 107L62 106ZM74 106L74 107L75 107L75 106ZM93 106L93 107L92 107L92 108L94 108L94 106ZM105 106L105 107L104 107L104 108L105 108L105 107L106 107L106 106ZM14 107L14 108L15 108L15 107ZM100 107L100 108L99 108L99 109L98 109L98 110L100 110L100 109L101 109L101 107ZM8 108L8 109L9 109L9 108ZM22 108L22 109L24 109L24 108ZM31 109L31 112L34 112L34 109ZM57 109L57 112L60 112L60 109ZM81 109L81 110L82 110L82 109ZM83 109L83 112L86 112L86 109ZM102 109L102 110L103 110L103 109ZM109 109L109 112L112 112L112 109ZM18 110L18 111L19 111L19 110ZM24 110L24 111L25 111L25 112L24 112L24 113L25 113L25 115L26 115L26 112L27 112L27 111L26 111L26 110ZM29 110L29 111L30 111L30 110ZM32 110L32 111L33 111L33 110ZM37 110L37 111L38 111L38 110ZM44 110L44 112L43 112L43 113L44 113L44 112L45 112L45 110ZM58 110L58 111L59 111L59 110ZM84 110L84 111L85 111L85 110ZM110 110L110 111L111 111L111 110ZM80 111L80 112L81 112L81 111ZM103 111L103 112L104 112L104 111ZM47 112L47 113L48 113L48 112ZM52 113L52 114L50 114L50 116L51 116L51 115L52 115L52 114L53 114L53 113ZM58 113L58 114L59 114L59 113ZM83 113L83 114L84 114L84 113ZM15 114L15 115L16 115L16 114ZM34 114L34 117L35 117L35 114ZM8 115L8 117L9 117L9 116L10 116L10 115ZM28 115L28 117L29 117L29 115ZM63 115L63 116L64 116L64 115ZM67 115L67 116L68 116L68 115ZM19 116L19 117L20 117L20 116ZM22 116L22 117L23 117L23 116ZM26 116L26 117L27 117L27 116ZM71 116L71 117L72 117L72 116ZM83 116L83 117L85 117L85 116ZM86 116L86 117L87 117L87 116ZM107 116L107 117L108 117L108 116ZM111 116L111 117L112 117L112 116ZM113 116L113 117L114 117L114 116ZM116 116L116 117L117 117L117 116ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM117 0L110 0L110 7L117 7ZM116 1L111 1L111 6L116 6ZM115 2L112 2L112 5L115 5ZM0 117L7 117L7 110L0 110ZM1 116L6 116L6 111L1 111ZM2 115L5 115L5 112L2 112Z\" fill=\"#000000\"/></g></g></svg>\n",
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"512\" height=\"512\" viewBox=\"0 0 512 512\"><rect x=\"0\" y=\"0\" width=\"512\" height=\"512\" fill=\"#fefefe\"/><g transform=\"scale(3.436)\"><g transform=\"translate(16.000,16.000)\"><path fill-rule=\"evenodd\" d=\"M8 0L8 4L10 4L10 5L8 5L8 7L9 7L9 6L10 6L10 8L12 8L12 9L10 9L10 10L9 10L9 9L8 9L8 10L9 10L9 12L6 12L6 13L8 13L8 14L6 14L6 15L7 15L7 16L5 16L5 15L4 15L4 14L5 14L5 13L4 13L4 14L3 14L3 11L2 11L2 10L4 10L4 11L7 11L7 10L5 10L5 9L7 9L7 8L4 8L4 9L2 9L2 8L0 8L0 10L1 10L1 12L0 12L0 13L1 13L1 12L2 12L2 15L0 15L0 17L3 17L3 18L0 18L0 19L1 19L1 21L0 21L0 22L1 22L1 21L2 21L2 20L4 20L4 21L5 21L5 22L4 22L4 24L3 24L3 23L2 23L2 25L4 25L4 26L3 26L3 27L0 27L0 28L3 28L3 29L2 29L2 30L3 30L3 31L1 31L1 29L0 29L0 31L1 31L1 32L0 32L0 33L1 33L1 34L0 34L0 35L1 35L1 34L2 34L2 33L1 33L1 32L3 32L3 31L4 31L4 35L3 35L3 37L4 37L4 38L2 38L2 39L1 39L1 40L4 40L4 41L3 41L3 44L4 44L4 45L3 45L3 46L2 46L2 47L1 47L1 45L2 45L2 43L1 43L1 42L2 42L2 41L1 41L1 42L0 42L0 43L1 43L1 44L0 44L0 50L1 50L1 51L0 51L0 54L1 54L1 55L0 55L0 56L1 56L1 55L2 55L2 56L3 56L3 57L4 57L4 58L3 58L3 59L4 59L4 60L2 60L2 59L1 59L1 60L0 60L0 63L1 63L1 65L0 65L0 66L2 66L2 67L0 67L0 75L3 75L3 78L2 78L2 77L1 77L1 76L0 76L0 78L1 78L1 79L2 79L2 80L0 80L0 81L1 81L1 82L0 82L0 83L1 83L1 84L2 84L2 85L1 85L1 86L0 86L0 88L1 88L1 89L3 89L3 90L2 90L2 91L3 91L3 92L4 92L4 93L8 93L8 92L9 92L9 90L12 90L12 91L13 91L13 90L16 90L16 91L15 91L15 92L17 92L17 93L16 93L16 94L18 94L18 95L16 95L16 96L15 96L15 95L13 95L13 94L14 94L14 92L12 92L12 95L10 95L10 97L9 97L9 95L8 95L8 94L5 94L5 95L4 95L4 94L2 94L2 93L1 93L1 95L2 95L2 96L3 96L3 97L2 97L2 98L1 98L1 97L0 97L0 101L1 101L1 99L3 99L3 101L2 101L2 102L3 102L3 101L4 101L4 103L1 103L1 102L0 102L0 107L1 107L1 106L2 106L2 108L1 108L1 109L3 109L3 106L2 106L2 104L3 104L3 105L4 105L4 104L5 104L5 101L10 101L10 103L9 103L9 104L8 104L8 105L7 105L7 104L6 104L6 105L5 105L5 106L4 106L4 107L5 107L5 108L6 108L6 109L7 109L7 108L6 108L6 107L8 107L8 106L9 106L9 108L8 108L8 110L9 110L9 108L11 108L11 106L12 106L12 107L13 107L13 108L14 108L14 109L15 109L15 108L16 108L16 107L17 107L17 109L18 109L18 108L20 108L20 107L19 107L19 106L20 106L20 104L21 104L21 108L22 108L22 110L24 110L24 109L23 109L23 107L22 107L22 104L21 104L21 103L22 103L22 102L21 102L21 103L20 103L20 104L19 104L19 103L18 103L18 101L16 101L16 102L17 102L17 105L18 105L18 104L19 104L19 106L18 106L18 107L17 107L17 106L14 106L14 107L13 107L13 105L14 105L14 104L13 104L13 105L11 105L11 104L10 104L10 103L12 103L12 102L11 102L11 101L12 101L12 100L11 100L11 98L10 98L10 97L12 97L12 95L13 95L13 96L15 96L15 97L16 97L16 96L17 96L17 97L20 97L20 98L21 98L21 97L20 97L20 96L18 96L18 95L20 95L20 94L18 94L18 92L17 92L17 91L18 91L18 90L19 90L19 91L21 91L21 90L22 90L22 88L23 88L23 89L24 89L24 91L22 91L22 92L19 92L19 93L22 93L22 94L21 94L21 95L22 95L22 94L23 94L23 93L24 93L24 94L25 94L25 95L26 95L26 96L24 96L24 95L23 95L23 96L22 96L22 97L24 97L24 98L23 98L23 99L22 99L22 100L21 100L21 99L16 99L16 98L12 98L12 99L16 99L16 100L20 100L20 101L19 101L19 102L20 102L20 101L23 101L23 103L24 103L24 101L25 101L25 104L23 104L23 105L25 105L25 106L24 106L24 107L26 107L26 108L25 108L25 109L26 109L26 110L27 110L27 111L25 111L25 112L23 112L23 113L24 113L24 114L22 114L22 115L21 115L21 116L20 116L20 115L19 115L19 114L18 114L18 113L17 113L17 110L15 110L15 112L13 112L13 111L11 111L11 110L10 110L10 112L9 112L9 111L8 111L8 113L10 113L10 114L11 114L11 113L10 113L10 112L12 112L12 113L14 113L14 114L16 114L16 117L22 117L22 116L23 116L23 115L26 115L26 114L25 114L25 112L27 112L27 111L28 111L28 112L29 112L29 113L35 113L35 112L36 112L36 113L37 113L37 116L35 116L35 117L37 117L37 116L38 116L38 117L41 117L41 115L40 115L40 116L39 116L39 115L38 115L38 113L39 113L39 114L42 114L42 113L43 113L43 115L47 115L47 113L49 113L49 112L48 112L48 111L50 111L50 114L48 114L48 115L49 115L49 116L48 116L48 117L49 117L49 116L50 116L50 117L51 117L51 116L50 116L50 114L51 114L51 115L53 115L53 114L52 114L52 113L51 113L51 111L50 111L50 110L49 110L49 109L52 109L52 108L53 108L53 107L52 107L52 108L51 108L51 107L50 107L50 106L51 106L51 105L50 105L50 106L49 106L49 108L48 108L48 107L47 107L47 106L46 106L46 107L45 107L45 108L46 108L46 109L45 109L45 110L46 110L46 111L45 111L45 112L46 112L46 111L47 111L47 113L46 113L46 114L45 114L45 113L43 113L43 112L44 112L44 111L43 111L43 112L42 112L42 111L41 111L41 110L40 110L40 112L39 112L39 111L38 111L38 110L39 110L39 109L41 109L41 107L40 107L40 106L41 106L41 104L43 104L43 108L44 108L44 105L46 105L46 104L47 104L47 105L49 105L49 104L51 104L51 103L52 103L52 105L53 105L53 106L54 106L54 105L55 105L55 108L56 108L56 109L54 109L54 110L53 110L53 111L52 111L52 112L53 112L53 111L54 111L54 113L55 113L55 114L56 114L56 115L55 115L55 116L56 116L56 115L57 115L57 117L59 117L59 116L58 116L58 115L61 115L61 116L60 116L60 117L62 117L62 116L63 116L63 115L64 115L64 116L65 116L65 114L63 114L63 115L62 115L62 114L61 114L61 113L66 113L66 112L65 112L65 111L64 111L64 109L63 109L63 107L64 107L64 108L66 108L66 110L69 110L69 109L71 109L71 110L70 110L70 112L71 112L71 113L70 113L70 117L71 117L71 113L72 113L72 114L73 114L73 113L74 113L74 114L75 114L75 115L76 115L76 117L77 117L77 115L76 115L76 114L79 114L79 115L78 115L78 117L79 117L79 116L80 116L80 117L81 117L81 116L80 116L80 111L82 111L82 114L81 114L81 115L83 115L83 113L87 113L87 114L88 114L88 115L86 115L86 114L84 114L84 115L86 115L86 117L88 117L88 116L90 116L90 117L92 117L92 115L93 115L93 116L94 116L94 117L99 117L99 116L100 116L100 117L103 117L103 116L104 116L104 114L106 114L106 115L105 115L105 117L109 117L109 116L110 116L110 117L112 117L112 115L114 115L114 114L112 114L112 113L115 113L115 114L117 114L117 113L115 113L115 112L113 112L113 109L114 109L114 111L116 111L116 112L117 112L117 111L116 111L116 110L117 110L117 107L116 107L116 106L115 106L115 108L116 108L116 109L114 109L114 107L108 107L108 109L106 109L106 108L104 108L104 106L105 106L105 104L106 104L106 103L107 103L107 101L106 101L106 100L107 100L107 99L108 99L108 100L111 100L111 99L109 99L109 98L108 98L108 97L109 97L109 95L110 95L110 94L111 94L111 93L113 93L113 92L112 92L112 89L111 89L111 88L110 88L110 87L114 87L114 86L115 86L115 85L117 85L117 84L116 84L116 83L115 83L115 85L114 85L114 83L113 83L113 82L115 82L115 81L114 81L114 79L113 79L113 78L112 78L112 79L113 79L113 82L111 82L111 81L112 81L112 80L111 80L111 81L110 81L110 82L109 82L109 80L110 80L110 79L111 79L111 77L112 77L112 76L113 76L113 77L114 77L114 78L115 78L115 80L116 80L116 81L117 81L117 80L116 80L116 77L117 77L117 75L116 75L116 74L115 74L115 73L111 73L111 72L108 72L108 71L109 71L109 69L110 69L110 71L112 71L112 64L113 64L113 66L114 66L114 67L115 67L115 66L116 66L116 67L117 67L117 66L116 66L116 62L117 62L117 59L116 59L116 58L117 58L117 56L114 56L114 57L113 57L113 55L116 55L116 53L117 53L117 51L116 51L116 50L117 50L117 48L116 48L116 49L115 49L115 48L113 48L113 47L111 47L111 48L110 48L110 47L109 47L109 46L111 46L111 43L112 43L112 46L115 46L115 47L116 47L116 46L117 46L117 43L116 43L116 42L117 42L117 41L116 41L116 40L113 40L113 38L115 38L115 39L116 39L116 38L117 38L117 35L116 35L116 34L117 34L117 32L116 32L116 30L117 30L117 27L115 27L115 26L114 26L114 23L115 23L115 25L117 25L117 23L116 23L116 22L114 22L114 23L113 23L113 21L115 21L115 20L113 20L113 18L114 18L114 19L116 19L116 18L117 18L117 17L116 17L116 14L117 14L117 13L116 13L116 12L115 12L115 13L112 13L112 15L111 15L111 16L112 16L112 17L111 17L111 18L109 18L109 15L110 15L110 14L111 14L111 12L112 12L112 11L114 11L114 9L115 9L115 11L116 11L116 10L117 10L117 8L113 8L113 9L112 9L112 8L111 8L111 10L112 10L112 11L111 11L111 12L110 12L110 9L109 9L109 6L108 6L108 5L107 5L107 4L106 4L106 5L105 5L105 4L104 4L104 1L103 1L103 0L102 0L102 3L101 3L101 2L100 2L100 4L102 4L102 5L101 5L101 6L100 6L100 5L98 5L98 7L97 7L97 6L96 6L96 5L97 5L97 3L98 3L98 2L99 2L99 0L91 0L91 1L89 1L89 2L88 2L88 3L87 3L87 4L86 4L86 3L85 3L85 4L84 4L84 3L83 3L83 2L84 2L84 1L85 1L85 0L81 0L81 2L82 2L82 3L83 3L83 4L82 4L82 7L81 7L81 6L80 6L80 7L79 7L79 4L78 4L78 3L80 3L80 4L81 4L81 3L80 3L80 0L74 0L74 1L73 1L73 3L74 3L74 4L78 4L78 5L77 5L77 6L76 6L76 8L77 8L77 9L75 9L75 8L74 8L74 7L75 7L75 5L74 5L74 7L73 7L73 5L71 5L71 4L72 4L72 3L71 3L71 2L72 2L72 0L70 0L70 1L69 1L69 0L67 0L67 1L65 1L65 0L64 0L64 1L62 1L62 2L61 2L61 3L60 3L60 4L59 4L59 3L58 3L58 1L59 1L59 0L58 0L58 1L56 1L56 0L51 0L51 1L49 1L49 0L47 0L47 2L46 2L46 1L43 1L43 0L42 0L42 1L41 1L41 0L38 0L38 3L39 3L39 1L40 1L40 3L41 3L41 4L40 4L40 5L39 5L39 4L38 4L38 5L36 5L36 7L37 7L37 8L35 8L35 4L36 4L36 3L35 3L35 4L34 4L34 3L33 3L33 4L30 4L30 3L32 3L32 2L34 2L34 0L32 0L32 1L31 1L31 0L30 0L30 1L29 1L29 0L27 0L27 1L26 1L26 4L25 4L25 1L24 1L24 0L23 0L23 1L24 1L24 4L22 4L22 3L23 3L23 2L22 2L22 1L21 1L21 0L20 0L20 2L19 2L19 3L20 3L20 4L21 4L21 5L20 5L20 9L18 9L18 8L19 8L19 6L18 6L18 5L17 5L17 4L18 4L18 0L17 0L17 3L15 3L15 2L14 2L14 3L13 3L13 0L12 0L12 1L11 1L11 0ZM36 0L36 1L35 1L35 2L37 2L37 0ZM86 0L86 2L87 2L87 0ZM100 0L100 1L101 1L101 0ZM105 0L105 3L109 3L109 1L108 1L108 2L106 2L106 1L107 1L107 0ZM27 1L27 3L28 3L28 4L26 4L26 5L25 5L25 4L24 4L24 5L21 5L21 9L20 9L20 10L18 10L18 9L17 9L17 8L18 8L18 6L17 6L17 7L16 7L16 4L15 4L15 5L14 5L14 4L13 4L13 3L12 3L12 2L11 2L11 5L10 5L10 6L11 6L11 7L12 7L12 8L13 8L13 9L12 9L12 10L11 10L11 12L9 12L9 13L12 13L12 10L13 10L13 11L15 11L15 16L16 16L16 17L14 17L14 16L13 16L13 15L14 15L14 14L11 14L11 16L10 16L10 15L8 15L8 17L11 17L11 16L12 16L12 19L16 19L16 17L17 17L17 18L18 18L18 19L17 19L17 20L18 20L18 21L16 21L16 22L15 22L15 23L16 23L16 24L17 24L17 26L16 26L16 27L15 27L15 25L14 25L14 24L13 24L13 23L14 23L14 22L11 22L11 21L10 21L10 23L8 23L8 22L7 22L7 21L8 21L8 20L10 20L10 19L9 19L9 18L8 18L8 19L6 19L6 20L7 20L7 21L6 21L6 22L7 22L7 23L5 23L5 25L6 25L6 26L8 26L8 25L10 25L10 26L11 26L11 27L8 27L8 28L7 28L7 27L5 27L5 26L4 26L4 27L3 27L3 28L7 28L7 29L4 29L4 30L7 30L7 29L8 29L8 30L9 30L9 28L10 28L10 29L13 29L13 30L11 30L11 33L10 33L10 34L9 34L9 35L10 35L10 34L11 34L11 36L9 36L9 37L8 37L8 36L7 36L7 35L6 35L6 36L5 36L5 35L4 35L4 37L5 37L5 38L8 38L8 39L6 39L6 40L7 40L7 41L6 41L6 42L7 42L7 43L4 43L4 44L7 44L7 43L8 43L8 44L9 44L9 42L10 42L10 41L9 41L9 39L10 39L10 40L11 40L11 42L12 42L12 43L10 43L10 46L11 46L11 47L9 47L9 46L7 46L7 45L6 45L6 46L5 46L5 45L4 45L4 46L3 46L3 48L1 48L1 50L2 50L2 52L3 52L3 53L4 53L4 54L5 54L5 55L3 55L3 54L2 54L2 53L1 53L1 54L2 54L2 55L3 55L3 56L7 56L7 55L8 55L8 54L9 54L9 55L10 55L10 57L9 57L9 58L10 58L10 57L11 57L11 56L12 56L12 58L11 58L11 59L10 59L10 60L9 60L9 61L10 61L10 60L11 60L11 61L12 61L12 62L13 62L13 63L12 63L12 64L11 64L11 63L10 63L10 62L8 62L8 61L6 61L6 62L8 62L8 64L9 64L9 66L11 66L11 67L9 67L9 68L8 68L8 69L9 69L9 68L10 68L10 69L11 69L11 68L12 68L12 69L13 69L13 68L12 68L12 67L13 67L13 66L12 66L12 65L14 65L14 64L17 64L17 65L15 65L15 66L14 66L14 69L15 69L15 68L16 68L16 69L17 69L17 70L14 70L14 71L13 71L13 74L12 74L12 75L11 75L11 73L10 73L10 71L8 71L8 70L7 70L7 69L6 69L6 68L7 68L7 67L8 67L8 66L7 66L7 65L6 65L6 64L7 64L7 63L3 63L3 62L2 62L2 60L1 60L1 63L3 63L3 64L5 64L5 65L2 65L2 66L3 66L3 67L2 67L2 69L1 69L1 70L4 70L4 71L3 71L3 72L5 72L5 71L6 71L6 72L7 72L7 73L3 73L3 75L4 75L4 74L5 74L5 76L8 76L8 77L6 77L6 78L7 78L7 79L5 79L5 81L6 81L6 82L9 82L9 83L10 83L10 81L9 81L9 80L11 80L11 82L12 82L12 79L11 79L11 78L10 78L10 79L9 79L9 76L8 76L8 75L10 75L10 77L11 77L11 76L12 76L12 78L13 78L13 80L14 80L14 81L13 81L13 82L15 82L15 85L14 85L14 87L13 87L13 88L14 88L14 89L15 89L15 88L16 88L16 87L15 87L15 86L17 86L17 89L16 89L16 90L17 90L17 89L18 89L18 88L19 88L19 90L20 90L20 89L21 89L21 88L19 88L19 87L21 87L21 86L22 86L22 87L23 87L23 88L24 88L24 89L26 89L26 90L27 90L27 91L24 91L24 92L25 92L25 93L27 93L27 94L26 94L26 95L27 95L27 96L26 96L26 99L27 99L27 100L28 100L28 102L29 102L29 100L28 100L28 99L31 99L31 100L30 100L30 106L28 106L28 107L27 107L27 106L26 106L26 107L27 107L27 108L26 108L26 109L27 109L27 108L28 108L28 107L30 107L30 108L34 108L34 107L36 107L36 108L38 108L38 109L39 109L39 108L40 108L40 107L39 107L39 106L38 106L38 104L40 104L40 103L39 103L39 102L41 102L41 103L43 103L43 104L46 104L46 102L47 102L47 104L49 104L49 103L51 103L51 102L52 102L52 103L54 103L54 104L53 104L53 105L54 105L54 104L55 104L55 105L56 105L56 103L55 103L55 101L57 101L57 100L56 100L56 99L58 99L58 104L57 104L57 108L58 108L58 107L60 107L60 108L61 108L61 110L62 110L62 111L61 111L61 112L62 112L62 111L63 111L63 112L64 112L64 111L63 111L63 109L62 109L62 107L63 107L63 106L65 106L65 107L67 107L67 109L69 109L69 107L70 107L70 108L74 108L74 107L75 107L75 110L73 110L73 111L72 111L72 110L71 110L71 112L73 112L73 111L77 111L77 112L75 112L75 114L76 114L76 113L77 113L77 112L78 112L78 111L79 111L79 109L80 109L80 110L82 110L82 109L81 109L81 105L80 105L80 104L81 104L81 103L83 103L83 104L84 104L84 103L86 103L86 104L85 104L85 105L83 105L83 107L84 107L84 108L88 108L88 107L89 107L89 108L90 108L90 109L91 109L91 107L92 107L92 112L91 112L91 110L89 110L89 109L87 109L87 110L89 110L89 112L88 112L88 111L87 111L87 113L88 113L88 114L89 114L89 115L92 115L92 114L93 114L93 115L94 115L94 114L95 114L95 113L98 113L98 115L103 115L103 114L104 114L104 113L106 113L106 112L107 112L107 113L108 113L108 111L106 111L106 109L104 109L104 110L105 110L105 111L103 111L103 108L100 108L100 107L103 107L103 106L104 106L104 104L105 104L105 103L106 103L106 102L105 102L105 101L104 101L104 102L105 102L105 103L104 103L104 104L103 104L103 102L101 102L101 101L103 101L103 100L105 100L105 99L106 99L106 98L107 98L107 97L108 97L108 96L107 96L107 95L103 95L103 94L102 94L102 93L104 93L104 94L107 94L107 93L108 93L108 91L105 91L105 87L106 87L106 86L107 86L107 85L108 85L108 84L105 84L105 83L106 83L106 82L107 82L107 81L108 81L108 80L107 80L107 79L109 79L109 78L110 78L110 75L111 75L111 76L112 76L112 75L111 75L111 74L110 74L110 73L109 73L109 74L107 74L107 75L106 75L106 74L105 74L105 72L106 72L106 70L105 70L105 71L104 71L104 72L103 72L103 73L104 73L104 76L105 76L105 78L107 78L107 79L104 79L104 77L102 77L102 75L103 75L103 74L102 74L102 73L101 73L101 72L102 72L102 71L103 71L103 70L104 70L104 69L107 69L107 71L108 71L108 69L109 69L109 68L110 68L110 67L109 67L109 66L110 66L110 64L111 64L111 62L113 62L113 61L111 61L111 62L110 62L110 61L109 61L109 62L107 62L107 61L108 61L108 59L106 59L106 57L105 57L105 59L104 59L104 60L103 60L103 58L104 58L104 57L103 57L103 58L101 58L101 57L102 57L102 55L104 55L104 56L106 56L106 54L107 54L107 55L109 55L109 56L110 56L110 55L111 55L111 56L112 56L112 55L113 55L113 54L115 54L115 53L116 53L116 51L115 51L115 50L114 50L114 49L113 49L113 48L112 48L112 50L111 50L111 49L110 49L110 48L109 48L109 49L107 49L107 51L106 51L106 49L105 49L105 47L106 47L106 46L107 46L107 45L110 45L110 43L111 43L111 42L110 42L110 43L108 43L108 42L109 42L109 41L110 41L110 40L112 40L112 38L113 38L113 37L115 37L115 38L116 38L116 36L115 36L115 35L114 35L114 33L115 33L115 30L114 30L114 33L113 33L113 37L110 37L110 36L109 36L109 35L108 35L108 32L107 32L107 31L104 31L104 32L105 32L105 33L107 33L107 35L106 35L106 34L105 34L105 35L104 35L104 36L103 36L103 34L104 34L104 33L103 33L103 34L101 34L101 33L100 33L100 31L99 31L99 30L100 30L100 29L101 29L101 32L102 32L102 31L103 31L103 30L104 30L104 29L107 29L107 28L108 28L108 27L109 27L109 30L110 30L110 29L111 29L111 28L112 28L112 26L111 26L111 25L110 25L110 26L107 26L107 27L106 27L106 26L105 26L105 27L104 27L104 28L103 28L103 23L104 23L104 24L105 24L105 25L107 25L107 23L108 23L108 24L109 24L109 21L108 21L108 22L107 22L107 20L108 20L108 19L109 19L109 18L108 18L108 19L106 19L106 18L105 18L105 19L103 19L103 18L104 18L104 17L103 17L103 18L102 18L102 19L101 19L101 16L103 16L103 15L104 15L104 16L106 16L106 17L108 17L108 15L109 15L109 13L110 13L110 12L109 12L109 9L107 9L107 8L105 8L105 7L106 7L106 6L105 6L105 5L104 5L104 4L103 4L103 7L102 7L102 6L101 6L101 7L102 7L102 8L104 8L104 9L103 9L103 10L101 10L101 8L100 8L100 6L99 6L99 8L98 8L98 12L99 12L99 13L97 13L97 14L98 14L98 15L94 15L94 14L93 14L93 13L95 13L95 12L97 12L97 7L96 7L96 6L95 6L95 5L96 5L96 3L97 3L97 2L98 2L98 1L94 1L94 2L93 2L93 1L92 1L92 3L91 3L91 7L92 7L92 8L90 8L90 9L91 9L91 10L90 10L90 11L89 11L89 12L88 12L88 10L89 10L89 8L88 8L88 6L89 6L89 7L90 7L90 6L89 6L89 5L90 5L90 4L89 4L89 5L88 5L88 4L87 4L87 5L88 5L88 6L87 6L87 8L88 8L88 9L87 9L87 10L86 10L86 11L87 11L87 13L85 13L85 14L84 14L84 12L85 12L85 10L84 10L84 11L78 11L78 10L79 10L79 7L78 7L78 6L77 6L77 8L78 8L78 9L77 9L77 11L78 11L78 12L79 12L79 13L78 13L78 14L77 14L77 13L75 13L75 12L76 12L76 10L75 10L75 9L74 9L74 8L73 8L73 7L72 7L72 6L71 6L71 5L70 5L70 6L69 6L69 7L68 7L68 6L67 6L67 7L68 7L68 8L67 8L67 9L68 9L68 8L69 8L69 10L68 10L68 12L67 12L67 10L65 10L65 9L66 9L66 8L64 8L64 9L63 9L63 8L62 8L62 5L63 5L63 4L62 4L62 5L61 5L61 9L57 9L57 10L56 10L56 9L55 9L55 8L54 8L54 10L53 10L53 8L52 8L52 6L53 6L53 7L54 7L54 6L53 6L53 5L55 5L55 4L58 4L58 3L57 3L57 2L56 2L56 1L55 1L55 2L56 2L56 3L53 3L53 4L52 4L52 5L51 5L51 3L52 3L52 2L53 2L53 1L51 1L51 3L50 3L50 4L49 4L49 5L47 5L47 3L49 3L49 2L47 2L47 3L46 3L46 5L45 5L45 4L44 4L44 3L43 3L43 2L42 2L42 5L45 5L45 7L44 7L44 6L43 6L43 7L42 7L42 6L41 6L41 7L42 7L42 9L39 9L39 8L40 8L40 6L39 6L39 7L38 7L38 6L37 6L37 7L38 7L38 10L37 10L37 11L36 11L36 9L30 9L30 10L31 10L31 11L29 11L29 12L28 12L28 10L26 10L26 9L28 9L28 8L29 8L29 7L30 7L30 4L29 4L29 3L30 3L30 2L29 2L29 1ZM68 1L68 3L67 3L67 4L68 4L68 5L69 5L69 4L70 4L70 3L69 3L69 1ZM70 1L70 2L71 2L71 1ZM75 1L75 3L76 3L76 1ZM82 1L82 2L83 2L83 1ZM20 2L20 3L22 3L22 2ZM28 2L28 3L29 3L29 2ZM63 2L63 3L64 3L64 6L63 6L63 7L64 7L64 6L65 6L65 7L66 7L66 6L65 6L65 3L64 3L64 2ZM89 2L89 3L90 3L90 2ZM94 3L94 4L93 4L93 6L92 6L92 7L93 7L93 6L94 6L94 8L96 8L96 7L95 7L95 6L94 6L94 5L95 5L95 3ZM24 5L24 9L23 9L23 6L22 6L22 10L21 10L21 12L22 12L22 11L23 11L23 12L27 12L27 11L25 11L25 10L24 10L24 9L26 9L26 8L27 8L27 5L26 5L26 8L25 8L25 5ZM31 5L31 8L34 8L34 5ZM50 5L50 7L49 7L49 6L48 6L48 7L49 7L49 8L46 8L46 9L45 9L45 8L44 8L44 7L43 7L43 8L44 8L44 9L42 9L42 10L43 10L43 12L41 12L41 10L39 10L39 11L37 11L37 13L39 13L39 15L40 15L40 18L43 18L43 19L40 19L40 20L38 20L38 22L39 22L39 23L37 23L37 20L36 20L36 21L35 21L35 22L34 22L34 21L33 21L33 20L35 20L35 19L38 19L38 17L36 17L36 18L35 18L35 16L32 16L32 15L36 15L36 11L35 11L35 10L34 10L34 11L35 11L35 12L34 12L34 14L33 14L33 12L32 12L32 11L33 11L33 10L32 10L32 11L31 11L31 12L29 12L29 13L28 13L28 14L29 14L29 15L28 15L28 16L27 16L27 15L26 15L26 14L25 14L25 13L24 13L24 14L23 14L23 13L22 13L22 14L23 14L23 15L21 15L21 14L19 14L19 12L16 12L16 13L17 13L17 14L16 14L16 15L17 15L17 14L18 14L18 16L20 16L20 19L18 19L18 20L20 20L20 19L21 19L21 20L22 20L22 22L21 22L21 24L20 24L20 25L22 25L22 26L21 26L21 27L22 27L22 28L21 28L21 29L19 29L19 30L17 30L17 29L18 29L18 28L19 28L19 27L20 27L20 26L19 26L19 25L18 25L18 27L17 27L17 29L16 29L16 28L15 28L15 33L13 33L13 34L12 34L12 33L11 33L11 34L12 34L12 35L13 35L13 36L14 36L14 34L15 34L15 33L16 33L16 34L17 34L17 35L18 35L18 36L15 36L15 37L14 37L14 38L13 38L13 37L12 37L12 36L11 36L11 38L10 38L10 39L11 39L11 40L12 40L12 41L13 41L13 42L14 42L14 44L13 44L13 45L14 45L14 46L13 46L13 48L8 48L8 47L7 47L7 46L6 46L6 47L5 47L5 48L3 48L3 49L8 49L8 50L6 50L6 51L7 51L7 52L4 52L4 53L7 53L7 54L6 54L6 55L7 55L7 54L8 54L8 53L9 53L9 54L10 54L10 55L13 55L13 56L16 56L16 58L14 58L14 57L13 57L13 58L12 58L12 59L15 59L15 60L12 60L12 61L15 61L15 62L14 62L14 63L15 63L15 62L16 62L16 63L17 63L17 62L18 62L18 59L19 59L19 60L20 60L20 59L21 59L21 58L22 58L22 60L21 60L21 63L19 63L19 64L18 64L18 66L15 66L15 67L16 67L16 68L18 68L18 66L20 66L20 69L18 69L18 70L17 70L17 71L16 71L16 73L15 73L15 71L14 71L14 73L15 73L15 74L13 74L13 75L12 75L12 76L13 76L13 75L16 75L16 74L17 74L17 75L18 75L18 74L19 74L19 73L20 73L20 74L21 74L21 75L20 75L20 78L21 78L21 81L23 81L23 82L20 82L20 83L21 83L21 84L20 84L20 85L19 85L19 84L18 84L18 85L17 85L17 86L18 86L18 87L19 87L19 86L21 86L21 84L22 84L22 85L23 85L23 84L22 84L22 83L26 83L26 82L27 82L27 83L28 83L28 84L30 84L30 85L29 85L29 87L28 87L28 89L27 89L27 90L29 90L29 88L32 88L32 89L31 89L31 91L32 91L32 92L31 92L31 95L32 95L32 96L33 96L33 98L34 98L34 97L35 97L35 98L36 98L36 99L37 99L37 101L36 101L36 100L35 100L35 99L34 99L34 101L33 101L33 100L32 100L32 101L31 101L31 105L32 105L32 106L30 106L30 107L32 107L32 106L33 106L33 105L34 105L34 104L35 104L35 102L34 102L34 101L36 101L36 103L37 103L37 104L38 104L38 103L37 103L37 101L38 101L38 102L39 102L39 101L40 101L40 100L41 100L41 99L39 99L39 100L38 100L38 97L39 97L39 96L38 96L38 93L40 93L40 92L41 92L41 93L42 93L42 91L41 91L41 90L40 90L40 88L41 88L41 89L44 89L44 87L42 87L42 88L41 88L41 86L42 86L42 85L44 85L44 86L45 86L45 87L46 87L46 88L47 88L47 87L48 87L48 86L49 86L49 85L50 85L50 86L51 86L51 87L49 87L49 88L48 88L48 89L46 89L46 90L45 90L45 91L47 91L47 93L46 93L46 96L47 96L47 97L46 97L46 99L43 99L43 100L42 100L42 101L41 101L41 102L42 102L42 101L44 101L44 100L46 100L46 101L50 101L50 102L51 102L51 101L52 101L52 102L53 102L53 101L52 101L52 100L53 100L53 99L52 99L52 100L51 100L51 101L50 101L50 100L46 100L46 99L48 99L48 97L49 97L49 99L50 99L50 98L52 98L52 97L51 97L51 95L52 95L52 96L53 96L53 97L55 97L55 98L56 98L56 96L55 96L55 95L56 95L56 94L57 94L57 93L58 93L58 92L59 92L59 94L58 94L58 95L59 95L59 94L61 94L61 95L60 95L60 96L59 96L59 98L58 98L58 96L57 96L57 98L58 98L58 99L60 99L60 96L61 96L61 97L62 97L62 98L63 98L63 97L64 97L64 100L63 100L63 99L61 99L61 100L59 100L59 101L60 101L60 102L61 102L61 103L60 103L60 104L61 104L61 103L62 103L62 106L63 106L63 105L64 105L64 104L65 104L65 106L67 106L67 107L69 107L69 104L68 104L68 102L69 102L69 101L65 101L65 97L64 97L64 96L67 96L67 97L66 97L66 98L67 98L67 99L69 99L69 98L70 98L70 99L71 99L71 98L72 98L72 97L75 97L75 99L76 99L76 101L75 101L75 100L74 100L74 101L75 101L75 102L76 102L76 104L74 104L74 102L73 102L73 101L72 101L72 102L71 102L71 101L70 101L70 103L71 103L71 104L72 104L72 103L73 103L73 104L74 104L74 105L73 105L73 106L72 106L72 105L70 105L70 107L74 107L74 106L75 106L75 105L76 105L76 104L77 104L77 105L78 105L78 103L77 103L77 102L79 102L79 104L80 104L80 103L81 103L81 102L82 102L82 101L83 101L83 103L84 103L84 101L83 101L83 100L85 100L85 101L86 101L86 102L87 102L87 101L86 101L86 100L87 100L87 99L88 99L88 102L92 102L92 103L87 103L87 104L88 104L88 105L86 105L86 106L88 106L88 105L89 105L89 106L91 106L91 104L92 104L92 107L95 107L95 106L94 106L94 105L95 105L95 104L96 104L96 103L97 103L97 104L98 104L98 105L99 105L99 107L100 107L100 106L101 106L101 104L102 104L102 103L100 103L100 101L99 101L99 103L97 103L97 102L96 102L96 101L97 101L97 100L95 100L95 98L94 98L94 100L92 100L92 99L90 99L90 98L89 98L89 99L88 99L88 98L87 98L87 97L89 97L89 96L91 96L91 98L92 98L92 97L93 97L93 96L94 96L94 97L95 97L95 96L94 96L94 95L97 95L97 97L96 97L96 99L98 99L98 100L99 100L99 99L100 99L100 100L101 100L101 99L102 99L102 100L103 100L103 99L105 99L105 98L106 98L106 96L105 96L105 97L103 97L103 99L102 99L102 95L100 95L100 94L99 94L99 95L98 95L98 93L97 93L97 92L99 92L99 93L102 93L102 92L101 92L101 91L98 91L98 90L100 90L100 89L101 89L101 90L103 90L103 88L104 88L104 87L105 87L105 86L104 86L104 83L105 83L105 80L104 80L104 79L102 79L102 80L101 80L101 77L100 77L100 78L99 78L99 75L100 75L100 76L101 76L101 74L99 74L99 73L100 73L100 72L101 72L101 71L102 71L102 70L103 70L103 68L104 68L104 67L105 67L105 68L107 68L107 69L108 69L108 68L107 68L107 67L108 67L108 66L109 66L109 63L107 63L107 62L106 62L106 61L107 61L107 60L106 60L106 61L105 61L105 60L104 60L104 61L101 61L101 62L103 62L103 63L102 63L102 64L104 64L104 61L105 61L105 62L106 62L106 63L107 63L107 64L108 64L108 65L102 65L102 66L103 66L103 68L101 68L101 63L100 63L100 62L99 62L99 63L100 63L100 64L98 64L98 65L97 65L97 64L96 64L96 67L95 67L95 66L94 66L94 64L95 64L95 62L94 62L94 61L92 61L92 59L93 59L93 60L95 60L95 57L96 57L96 58L97 58L97 59L98 59L98 58L97 58L97 57L99 57L99 59L100 59L100 60L99 60L99 61L100 61L100 60L101 60L101 58L100 58L100 57L101 57L101 55L102 55L102 54L106 54L106 53L107 53L107 54L109 54L109 55L110 55L110 54L111 54L111 55L112 55L112 53L114 53L114 50L112 50L112 53L111 53L111 51L110 51L110 53L109 53L109 52L108 52L108 51L109 51L109 50L108 50L108 51L107 51L107 52L106 52L106 51L105 51L105 49L104 49L104 50L103 50L103 51L102 51L102 50L101 50L101 49L103 49L103 48L104 48L104 47L105 47L105 45L106 45L106 44L104 44L104 43L107 43L107 44L108 44L108 43L107 43L107 42L108 42L108 41L109 41L109 40L110 40L110 37L109 37L109 36L108 36L108 35L107 35L107 36L106 36L106 37L103 37L103 39L102 39L102 38L99 38L99 39L98 39L98 36L96 36L96 37L97 37L97 38L96 38L96 39L95 39L95 38L93 38L93 37L94 37L94 35L95 35L95 33L97 33L97 34L96 34L96 35L98 35L98 33L99 33L99 32L98 32L98 29L99 29L99 28L98 28L98 25L99 25L99 26L100 26L100 28L101 28L101 29L102 29L102 30L103 30L103 28L101 28L101 26L100 26L100 25L102 25L102 23L101 23L101 22L100 22L100 25L99 25L99 23L98 23L98 22L99 22L99 21L103 21L103 22L104 22L104 20L103 20L103 19L102 19L102 20L101 20L101 19L100 19L100 17L99 17L99 16L98 16L98 17L99 17L99 19L100 19L100 20L99 20L99 21L98 21L98 18L97 18L97 16L96 16L96 18L95 18L95 17L94 17L94 19L93 19L93 17L92 17L92 14L91 14L91 13L92 13L92 10L91 10L91 11L90 11L90 12L91 12L91 13L90 13L90 14L91 14L91 15L90 15L90 16L89 16L89 15L88 15L88 14L87 14L87 15L85 15L85 16L84 16L84 15L82 15L82 14L83 14L83 13L82 13L82 12L80 12L80 13L79 13L79 15L78 15L78 16L79 16L79 17L78 17L78 18L79 18L79 17L80 17L80 19L78 19L78 20L77 20L77 18L75 18L75 17L77 17L77 14L75 14L75 15L74 15L74 12L71 12L71 13L73 13L73 15L74 15L74 16L73 16L73 17L74 17L74 18L75 18L75 19L76 19L76 20L74 20L74 19L73 19L73 18L72 18L72 19L73 19L73 20L74 20L74 21L73 21L73 24L72 24L72 23L71 23L71 22L72 22L72 20L71 20L71 18L70 18L70 17L72 17L72 16L70 16L70 15L72 15L72 14L70 14L70 12L69 12L69 14L70 14L70 15L66 15L66 17L68 17L68 16L70 16L70 17L69 17L69 18L70 18L70 19L69 19L69 21L70 21L70 23L71 23L71 24L69 24L69 25L68 25L68 18L67 18L67 20L66 20L66 19L62 19L62 18L64 18L64 17L62 17L62 16L59 16L59 18L57 18L57 17L58 17L58 16L56 16L56 15L53 15L53 14L54 14L54 12L53 12L53 10L52 10L52 8L51 8L51 5ZM57 5L57 8L60 8L60 5ZM83 5L83 8L86 8L86 5ZM12 6L12 7L13 7L13 8L14 8L14 7L15 7L15 6L14 6L14 7L13 7L13 6ZM28 6L28 7L29 7L29 6ZM32 6L32 7L33 7L33 6ZM46 6L46 7L47 7L47 6ZM55 6L55 7L56 7L56 6ZM58 6L58 7L59 7L59 6ZM70 6L70 9L71 9L71 10L70 10L70 11L71 11L71 10L72 10L72 11L74 11L74 10L73 10L73 8L72 8L72 9L71 9L71 6ZM84 6L84 7L85 7L85 6ZM104 6L104 7L105 7L105 6ZM107 6L107 7L108 7L108 6ZM80 7L80 8L81 8L81 7ZM15 8L15 10L16 10L16 11L18 11L18 10L17 10L17 9L16 9L16 8ZM49 8L49 9L50 9L50 8ZM1 9L1 10L2 10L2 9ZM44 9L44 12L46 12L46 13L44 13L44 14L42 14L42 15L41 15L41 16L42 16L42 15L44 15L44 18L45 18L45 19L44 19L44 20L45 20L45 19L46 19L46 18L45 18L45 17L47 17L47 18L48 18L48 17L49 17L49 15L51 15L51 16L50 16L50 19L51 19L51 20L50 20L50 21L48 21L48 20L49 20L49 19L47 19L47 20L46 20L46 21L44 21L44 22L45 22L45 23L44 23L44 25L45 25L45 27L44 27L44 26L42 26L42 25L43 25L43 24L42 24L42 23L43 23L43 20L41 20L41 21L39 21L39 22L40 22L40 23L39 23L39 24L37 24L37 23L34 23L34 22L33 22L33 23L32 23L32 21L31 21L31 23L32 23L32 25L33 25L33 27L32 27L32 30L34 30L34 29L35 29L35 31L36 31L36 33L35 33L35 35L36 35L36 36L37 36L37 37L38 37L38 39L39 39L39 40L40 40L40 41L41 41L41 43L39 43L39 42L38 42L38 40L37 40L37 38L36 38L36 37L35 37L35 36L34 36L34 35L32 35L32 36L33 36L33 38L32 38L32 37L31 37L31 39L33 39L33 41L32 41L32 43L33 43L33 44L34 44L34 45L33 45L33 46L34 46L34 47L33 47L33 49L32 49L32 48L31 48L31 51L32 51L32 52L28 52L28 50L29 50L29 49L30 49L30 47L29 47L29 46L31 46L31 45L30 45L30 44L28 44L28 42L30 42L30 40L27 40L27 41L28 41L28 42L26 42L26 41L25 41L25 40L26 40L26 39L29 39L29 37L30 37L30 36L28 36L28 35L29 35L29 34L30 34L30 29L29 29L29 28L31 28L31 27L29 27L29 26L30 26L30 25L31 25L31 24L30 24L30 25L28 25L28 24L27 24L27 25L26 25L26 27L29 27L29 28L28 28L28 30L29 30L29 33L28 33L28 31L27 31L27 28L25 28L25 24L23 24L23 25L24 25L24 26L22 26L22 27L23 27L23 28L25 28L25 29L26 29L26 31L25 31L25 30L24 30L24 31L23 31L23 30L22 30L22 31L23 31L23 32L24 32L24 31L25 31L25 33L26 33L26 36L24 36L24 35L23 35L23 33L21 33L21 34L20 34L20 35L23 35L23 36L20 36L20 37L19 37L19 39L18 39L18 37L17 37L17 39L15 39L15 41L14 41L14 42L15 42L15 41L16 41L16 40L17 40L17 42L16 42L16 43L15 43L15 44L14 44L14 45L15 45L15 46L14 46L14 47L15 47L15 46L19 46L19 44L20 44L20 45L22 45L22 42L23 42L23 43L24 43L24 41L25 41L25 43L26 43L26 44L25 44L25 45L26 45L26 47L25 47L25 46L24 46L24 45L23 45L23 46L22 46L22 47L21 47L21 48L19 48L19 49L23 49L23 52L22 52L22 53L21 53L21 54L22 54L22 55L21 55L21 56L22 56L22 58L23 58L23 61L22 61L22 62L23 62L23 64L26 64L26 63L27 63L27 64L28 64L28 58L29 58L29 60L30 60L30 55L32 55L32 56L34 56L34 55L36 55L36 56L35 56L35 60L37 60L37 61L35 61L35 62L34 62L34 61L33 61L33 62L32 62L32 61L31 61L31 62L29 62L29 63L33 63L33 64L34 64L34 65L33 65L33 66L32 66L32 65L31 65L31 67L29 67L29 66L28 66L28 65L24 65L24 66L23 66L23 67L22 67L22 65L21 65L21 67L22 67L22 68L21 68L21 69L20 69L20 71L18 71L18 72L17 72L17 73L18 73L18 72L22 72L22 73L21 73L21 74L22 74L22 75L21 75L21 77L23 77L23 76L24 76L24 77L25 77L25 78L24 78L24 79L23 79L23 78L22 78L22 80L23 80L23 81L24 81L24 82L26 82L26 81L27 81L27 82L29 82L29 83L30 83L30 82L29 82L29 81L30 81L30 80L31 80L31 81L33 81L33 80L34 80L34 82L35 82L35 80L36 80L36 81L37 81L37 82L36 82L36 83L35 83L35 85L36 85L36 86L35 86L35 88L33 88L33 89L34 89L34 90L32 90L32 91L35 91L35 92L37 92L37 93L34 93L34 92L33 92L33 93L34 93L34 95L35 95L35 97L36 97L36 98L37 98L37 97L38 97L38 96L37 96L37 95L35 95L35 94L37 94L37 93L38 93L38 92L39 92L39 91L40 91L40 90L39 90L39 88L40 88L40 87L39 87L39 86L38 86L38 85L37 85L37 84L38 84L38 83L39 83L39 84L40 84L40 86L41 86L41 85L42 85L42 84L40 84L40 83L44 83L44 85L46 85L46 86L47 86L47 84L48 84L48 85L49 85L49 83L51 83L51 84L52 84L52 83L55 83L55 82L53 82L53 80L52 80L52 79L48 79L48 78L47 78L47 79L48 79L48 80L47 80L47 82L48 82L48 81L50 81L50 80L51 80L51 81L52 81L52 82L49 82L49 83L47 83L47 84L46 84L46 80L45 80L45 79L46 79L46 76L47 76L47 77L50 77L50 78L53 78L53 79L54 79L54 81L55 81L55 80L58 80L58 81L57 81L57 82L58 82L58 81L62 81L62 79L65 79L65 80L66 80L66 81L63 81L63 82L62 82L62 83L61 83L61 86L62 86L62 87L60 87L60 88L58 88L58 87L57 87L57 88L56 88L56 90L55 90L55 88L54 88L54 87L55 87L55 86L56 86L56 85L54 85L54 84L53 84L53 86L52 86L52 87L51 87L51 88L50 88L50 91L48 91L48 93L47 93L47 94L48 94L48 95L47 95L47 96L50 96L50 95L51 95L51 93L52 93L52 94L53 94L53 93L54 93L54 95L53 95L53 96L54 96L54 95L55 95L55 93L56 93L56 90L57 90L57 91L60 91L60 89L61 89L61 91L62 91L62 92L64 92L64 91L67 91L67 90L68 90L68 92L69 92L69 93L70 93L70 94L69 94L69 95L68 95L68 96L70 96L70 98L71 98L71 97L72 97L72 96L73 96L73 94L74 94L74 95L75 95L75 97L76 97L76 95L78 95L78 97L77 97L77 98L76 98L76 99L77 99L77 101L76 101L76 102L77 102L77 101L78 101L78 99L80 99L80 101L79 101L79 102L80 102L80 101L81 101L81 99L82 99L82 100L83 100L83 98L82 98L82 97L84 97L84 99L85 99L85 100L86 100L86 99L87 99L87 98L86 98L86 99L85 99L85 97L86 97L86 96L88 96L88 91L86 91L86 88L85 88L85 87L82 87L82 86L81 86L81 85L82 85L82 84L81 84L81 83L82 83L82 82L83 82L83 81L84 81L84 80L86 80L86 79L90 79L90 80L89 80L89 81L86 81L86 82L87 82L87 84L88 84L88 83L90 83L90 84L91 84L91 79L92 79L92 81L93 81L93 83L92 83L92 84L93 84L93 86L92 86L92 85L89 85L89 86L88 86L88 85L87 85L87 87L89 87L89 88L88 88L88 90L90 90L90 91L89 91L89 92L90 92L90 94L89 94L89 95L92 95L92 96L93 96L93 95L94 95L94 94L93 94L93 92L94 92L94 93L95 93L95 94L96 94L96 92L97 92L97 89L98 89L98 87L99 87L99 89L100 89L100 87L102 87L102 88L101 88L101 89L102 89L102 88L103 88L103 85L102 85L102 84L100 84L100 81L101 81L101 80L100 80L100 79L99 79L99 78L98 78L98 77L96 77L96 78L95 78L95 77L94 77L94 78L93 78L93 75L94 75L94 74L93 74L93 75L92 75L92 76L91 76L91 74L92 74L92 72L93 72L93 71L94 71L94 69L95 69L95 68L94 68L94 66L93 66L93 67L91 67L91 65L93 65L93 64L94 64L94 62L90 62L90 61L89 61L89 60L88 60L88 61L85 61L85 62L88 62L88 63L92 63L92 64L90 64L90 66L89 66L89 64L88 64L88 65L87 65L87 66L86 66L86 64L87 64L87 63L83 63L83 61L82 61L82 60L81 60L81 59L79 59L79 58L80 58L80 57L82 57L82 56L88 56L88 58L87 58L87 59L90 59L90 60L91 60L91 59L92 59L92 58L93 58L93 59L94 59L94 58L93 58L93 57L92 57L92 58L90 58L90 56L88 56L88 54L86 54L86 53L91 53L91 54L89 54L89 55L92 55L92 56L93 56L93 55L94 55L94 54L93 54L93 55L92 55L92 53L93 53L93 52L94 52L94 53L95 53L95 54L96 54L96 53L95 53L95 52L97 52L97 51L96 51L96 50L95 50L95 49L97 49L97 50L98 50L98 53L97 53L97 54L98 54L98 56L99 56L99 57L100 57L100 54L98 54L98 53L99 53L99 51L101 51L101 50L98 50L98 49L100 49L100 47L102 47L102 48L103 48L103 46L104 46L104 44L103 44L103 46L102 46L102 45L101 45L101 44L102 44L102 43L103 43L103 42L104 42L104 41L103 41L103 42L102 42L102 43L101 43L101 44L100 44L100 43L99 43L99 41L101 41L101 40L102 40L102 39L101 39L101 40L100 40L100 39L99 39L99 41L97 41L97 40L98 40L98 39L96 39L96 41L97 41L97 42L98 42L98 44L97 44L97 43L96 43L96 42L95 42L95 39L92 39L92 38L90 38L90 39L89 39L89 40L88 40L88 39L87 39L87 40L88 40L88 41L85 41L85 42L84 42L84 41L83 41L83 42L84 42L84 43L86 43L86 44L85 44L85 45L86 45L86 44L87 44L87 45L88 45L88 43L87 43L87 42L88 42L88 41L89 41L89 43L90 43L90 44L91 44L91 42L92 42L92 44L93 44L93 46L92 46L92 49L91 49L91 50L88 50L88 49L89 49L89 48L88 48L88 47L87 47L87 46L84 46L84 45L83 45L83 43L82 43L82 42L81 42L81 43L80 43L80 44L81 44L81 45L80 45L80 46L82 46L82 45L83 45L83 46L84 46L84 47L79 47L79 46L77 46L77 45L76 45L76 43L75 43L75 40L76 40L76 39L77 39L77 41L76 41L76 42L77 42L77 43L79 43L79 42L77 42L77 41L80 41L80 40L81 40L81 37L82 37L82 35L81 35L81 34L82 34L82 32L81 32L81 29L80 29L80 33L78 33L78 32L77 32L77 31L79 31L79 27L80 27L80 28L82 28L82 29L83 29L83 30L85 30L85 28L86 28L86 30L87 30L87 33L88 33L88 34L89 34L89 35L90 35L90 36L91 36L91 37L93 37L93 35L94 35L94 34L93 34L93 31L94 31L94 30L95 30L95 32L94 32L94 33L95 33L95 32L97 32L97 29L94 29L94 28L97 28L97 27L94 27L94 26L96 26L96 25L97 25L97 22L96 22L96 21L97 21L97 19L94 19L94 20L93 20L93 19L92 19L92 17L91 17L91 16L90 16L90 18L89 18L89 19L90 19L90 20L88 20L88 21L87 21L87 20L86 20L86 19L88 19L88 18L87 18L87 17L85 17L85 18L86 18L86 19L84 19L84 17L83 17L83 16L81 16L81 14L82 14L82 13L81 13L81 14L80 14L80 17L83 17L83 18L82 18L82 19L80 19L80 21L79 21L79 20L78 20L78 21L77 21L77 23L78 23L78 24L79 24L79 23L80 23L80 25L79 25L79 26L78 26L78 25L77 25L77 24L76 24L76 23L75 23L75 22L76 22L76 21L74 21L74 23L75 23L75 24L73 24L73 25L74 25L74 28L75 28L75 29L73 29L73 26L72 26L72 25L71 25L71 26L70 26L70 25L69 25L69 27L67 27L67 26L68 26L68 25L67 25L67 22L66 22L66 21L64 21L64 23L63 23L63 21L62 21L62 23L63 23L63 24L61 24L61 22L60 22L60 23L58 23L58 22L59 22L59 20L62 20L62 19L60 19L60 18L62 18L62 17L60 17L60 18L59 18L59 19L57 19L57 18L56 18L56 16L55 16L55 17L53 17L53 16L52 16L52 14L53 14L53 12L52 12L52 10L50 10L50 11L49 11L49 10L47 10L47 9L46 9L46 10L47 10L47 11L45 11L45 9ZM62 9L62 10L58 10L58 11L61 11L61 12L58 12L58 13L57 13L57 11L55 11L55 12L56 12L56 14L58 14L58 15L60 15L60 14L61 14L61 15L62 15L62 14L63 14L63 11L62 11L62 10L63 10L63 9ZM80 9L80 10L83 10L83 9ZM94 9L94 12L95 12L95 10L96 10L96 9ZM99 9L99 10L100 10L100 13L99 13L99 14L100 14L100 13L101 13L101 12L104 12L104 13L103 13L103 14L104 14L104 15L108 15L108 13L109 13L109 12L107 12L107 13L106 13L106 11L108 11L108 10L107 10L107 9L106 9L106 11L105 11L105 12L104 12L104 10L105 10L105 9L104 9L104 10L103 10L103 11L101 11L101 10L100 10L100 9ZM39 11L39 12L40 12L40 13L41 13L41 12L40 12L40 11ZM47 11L47 13L46 13L46 15L45 15L45 16L47 16L47 17L48 17L48 15L49 15L49 14L52 14L52 12L50 12L50 13L49 13L49 12L48 12L48 11ZM64 11L64 14L66 14L66 12L65 12L65 11ZM31 12L31 13L32 13L32 12ZM61 12L61 14L62 14L62 12ZM29 13L29 14L30 14L30 15L29 15L29 17L30 17L30 20L31 20L31 19L32 19L32 20L33 20L33 19L35 19L35 18L34 18L34 17L33 17L33 18L31 18L31 17L32 17L32 16L31 16L31 17L30 17L30 15L31 15L31 14L30 14L30 13ZM47 13L47 15L48 15L48 13ZM58 13L58 14L59 14L59 13ZM104 13L104 14L106 14L106 13ZM115 13L115 14L114 14L114 15L113 15L113 17L114 17L114 18L115 18L115 14L116 14L116 13ZM24 14L24 15L25 15L25 16L24 16L24 19L22 19L22 17L21 17L21 19L22 19L22 20L23 20L23 22L24 22L24 23L30 23L30 21L28 21L28 19L29 19L29 18L28 18L28 19L27 19L27 16L26 16L26 15L25 15L25 14ZM37 14L37 16L38 16L38 14ZM2 15L2 16L3 16L3 15ZM20 15L20 16L21 16L21 15ZM63 15L63 16L65 16L65 15ZM75 15L75 16L76 16L76 15ZM87 15L87 16L88 16L88 17L89 17L89 16L88 16L88 15ZM93 15L93 16L94 16L94 15ZM100 15L100 16L101 16L101 15ZM25 16L25 20L24 20L24 21L25 21L25 20L26 20L26 21L27 21L27 22L28 22L28 21L27 21L27 19L26 19L26 16ZM51 16L51 17L52 17L52 18L51 18L51 19L53 19L53 20L52 20L52 21L50 21L50 22L49 22L49 23L50 23L50 22L53 22L53 21L54 21L54 20L55 20L55 21L56 21L56 22L54 22L54 23L51 23L51 24L50 24L50 26L51 26L51 27L49 27L49 24L48 24L48 26L46 26L46 27L49 27L49 28L46 28L46 29L45 29L45 30L44 30L44 27L43 27L43 29L41 29L41 27L40 27L40 25L41 25L41 23L40 23L40 25L38 25L38 26L34 26L34 28L35 28L35 27L38 27L38 28L37 28L37 29L39 29L39 31L38 31L38 30L36 30L36 31L37 31L37 32L39 32L39 31L40 31L40 33L41 33L41 31L40 31L40 29L41 29L41 30L44 30L44 31L42 31L42 32L44 32L44 33L45 33L45 34L46 34L46 35L47 35L47 33L45 33L45 32L44 32L44 31L45 31L45 30L46 30L46 31L48 31L48 30L49 30L49 29L50 29L50 30L51 30L51 31L52 31L52 33L55 33L55 36L54 36L54 35L53 35L53 34L51 34L51 35L49 35L49 34L50 34L50 33L51 33L51 32L50 32L50 31L49 31L49 32L50 32L50 33L48 33L48 35L49 35L49 36L45 36L45 37L44 37L44 35L43 35L43 38L45 38L45 37L46 37L46 38L47 38L47 37L48 37L48 39L50 39L50 38L49 38L49 37L51 37L51 38L52 38L52 37L51 37L51 35L52 35L52 36L53 36L53 37L54 37L54 38L56 38L56 37L57 37L57 39L56 39L56 40L55 40L55 39L52 39L52 42L51 42L51 40L50 40L50 42L49 42L49 41L48 41L48 43L49 43L49 44L47 44L47 45L46 45L46 44L45 44L45 45L44 45L44 43L45 43L45 41L47 41L47 39L45 39L45 40L44 40L44 39L43 39L43 40L44 40L44 42L43 42L43 41L42 41L42 42L43 42L43 43L42 43L42 45L41 45L41 44L40 44L40 45L39 45L39 44L38 44L38 42L37 42L37 41L35 41L35 38L34 38L34 41L35 41L35 42L36 42L36 43L34 43L34 44L36 44L36 43L37 43L37 44L38 44L38 45L35 45L35 46L37 46L37 47L38 47L38 48L36 48L36 49L38 49L38 50L36 50L36 51L38 51L38 52L39 52L39 54L38 54L38 53L36 53L36 52L35 52L35 51L34 51L34 52L32 52L32 53L30 53L30 54L28 54L28 53L27 53L27 54L28 54L28 55L25 55L25 58L24 58L24 55L23 55L23 58L24 58L24 61L23 61L23 62L25 62L25 63L26 63L26 62L27 62L27 59L25 59L25 58L26 58L26 57L28 57L28 56L29 56L29 55L30 55L30 54L32 54L32 55L33 55L33 54L35 54L35 53L36 53L36 55L39 55L39 56L37 56L37 57L36 57L36 58L37 58L37 57L38 57L38 58L39 58L39 59L37 59L37 60L38 60L38 61L37 61L37 64L35 64L35 63L36 63L36 62L35 62L35 63L34 63L34 62L33 62L33 63L34 63L34 64L35 64L35 65L36 65L36 66L33 66L33 67L32 67L32 68L33 68L33 69L32 69L32 70L31 70L31 69L30 69L30 68L29 68L29 67L28 67L28 66L27 66L27 67L28 67L28 68L24 68L24 69L27 69L27 70L28 70L28 69L29 69L29 70L31 70L31 72L30 72L30 71L28 71L28 72L27 72L27 71L26 71L26 70L25 70L25 71L24 71L24 70L23 70L23 69L22 69L22 70L21 70L21 71L24 71L24 72L23 72L23 73L24 73L24 76L25 76L25 77L27 77L27 75L28 75L28 76L29 76L29 77L30 77L30 78L31 78L31 76L32 76L32 79L31 79L31 80L32 80L32 79L34 79L34 77L33 77L33 76L32 76L32 74L33 74L33 75L34 75L34 76L35 76L35 77L36 77L36 78L35 78L35 79L37 79L37 80L38 80L38 78L37 78L37 77L39 77L39 78L41 78L41 79L39 79L39 81L41 81L41 82L42 82L42 80L43 80L43 81L45 81L45 80L43 80L43 79L42 79L42 78L44 78L44 79L45 79L45 78L44 78L44 77L45 77L45 76L44 76L44 75L49 75L49 76L51 76L51 77L53 77L53 78L54 78L54 77L55 77L55 79L56 79L56 77L57 77L57 78L59 78L59 79L58 79L58 80L61 80L61 79L62 79L62 78L61 78L61 77L60 77L60 76L62 76L62 77L64 77L64 78L67 78L67 79L71 79L71 78L68 78L68 77L69 77L69 76L68 76L68 75L70 75L70 76L72 76L72 75L74 75L74 76L73 76L73 77L72 77L72 78L73 78L73 77L74 77L74 78L76 78L76 79L73 79L73 80L75 80L75 81L76 81L76 80L77 80L77 82L78 82L78 81L80 81L80 82L82 82L82 81L83 81L83 80L84 80L84 79L82 79L82 78L81 78L81 77L82 77L82 76L83 76L83 75L84 75L84 77L83 77L83 78L85 78L85 77L87 77L87 76L86 76L86 75L87 75L87 73L88 73L88 72L89 72L89 73L91 73L91 72L90 72L90 71L93 71L93 70L90 70L90 71L89 71L89 69L90 69L90 67L89 67L89 66L87 66L87 67L85 67L85 64L83 64L83 63L82 63L82 61L81 61L81 64L82 64L82 65L83 65L83 66L84 66L84 67L81 67L81 68L83 68L83 70L84 70L84 71L83 71L83 72L84 72L84 73L83 73L83 75L82 75L82 72L80 72L80 75L79 75L79 76L78 76L78 75L76 75L76 76L78 76L78 77L77 77L77 78L76 78L76 77L74 77L74 76L75 76L75 75L74 75L74 74L76 74L76 73L78 73L78 74L79 74L79 71L82 71L82 69L78 69L78 67L76 67L76 66L81 66L81 65L79 65L79 64L80 64L80 62L79 62L79 60L77 60L77 59L78 59L78 58L79 58L79 57L80 57L80 54L81 54L81 55L82 55L82 54L83 54L83 55L84 55L84 54L85 54L85 55L86 55L86 54L85 54L85 52L86 52L86 50L87 50L87 49L86 49L86 47L84 47L84 49L85 49L85 50L84 50L84 51L83 51L83 50L81 50L81 52L80 52L80 51L79 51L79 52L78 52L78 51L73 51L73 48L74 48L74 50L76 50L76 49L77 49L77 50L80 50L80 49L82 49L82 48L80 48L80 49L78 49L78 48L79 48L79 47L77 47L77 46L76 46L76 45L74 45L74 46L73 46L73 48L71 48L71 47L72 47L72 44L71 44L71 46L70 46L70 45L69 45L69 44L68 44L68 42L69 42L69 43L72 43L72 42L73 42L73 43L74 43L74 44L75 44L75 43L74 43L74 41L72 41L72 42L70 42L70 41L71 41L71 39L72 39L72 37L73 37L73 36L72 36L72 37L71 37L71 33L75 33L75 34L76 34L76 36L75 36L75 39L73 39L73 40L75 40L75 39L76 39L76 36L77 36L77 38L79 38L79 39L80 39L80 37L81 37L81 36L80 36L80 35L78 35L78 36L77 36L77 34L78 34L78 33L77 33L77 32L76 32L76 33L75 33L75 31L77 31L77 29L76 29L76 30L75 30L75 31L74 31L74 30L73 30L73 29L71 29L71 30L70 30L70 29L69 29L69 30L67 30L67 27L64 27L64 25L65 25L65 24L66 24L66 23L64 23L64 25L60 25L60 24L58 24L58 23L57 23L57 21L58 21L58 20L56 20L56 19L54 19L54 18L53 18L53 17L52 17L52 16ZM5 17L5 18L3 18L3 19L4 19L4 20L5 20L5 18L7 18L7 17ZM18 17L18 18L19 18L19 17ZM82 19L82 20L83 20L83 21L82 21L82 22L81 22L81 21L80 21L80 22L81 22L81 25L80 25L80 27L82 27L82 28L83 28L83 27L86 27L86 28L87 28L87 29L88 29L88 32L90 32L90 31L89 31L89 29L88 29L88 28L89 28L89 26L91 26L91 24L92 24L92 21L93 21L93 20L92 20L92 19L91 19L91 20L90 20L90 21L89 21L89 23L88 23L88 22L87 22L87 23L88 23L88 24L86 24L86 26L83 26L83 25L82 25L82 24L84 24L84 25L85 25L85 24L84 24L84 23L86 23L86 22L84 22L84 21L85 21L85 20L84 20L84 19ZM110 19L110 20L111 20L111 21L113 21L113 20L111 20L111 19ZM70 20L70 21L71 21L71 20ZM91 20L91 21L90 21L90 24L91 24L91 21L92 21L92 20ZM19 21L19 22L20 22L20 21ZM47 21L47 22L46 22L46 23L45 23L45 24L46 24L46 25L47 25L47 24L46 24L46 23L47 23L47 22L48 22L48 21ZM78 21L78 22L79 22L79 21ZM94 21L94 23L93 23L93 25L92 25L92 27L90 27L90 28L92 28L92 27L93 27L93 28L94 28L94 27L93 27L93 25L94 25L94 23L96 23L96 22L95 22L95 21ZM105 21L105 22L106 22L106 23L107 23L107 22L106 22L106 21ZM17 22L17 24L19 24L19 23L18 23L18 22ZM83 22L83 23L84 23L84 22ZM0 23L0 25L1 25L1 23ZM11 23L11 24L12 24L12 23ZM33 23L33 25L35 25L35 24L34 24L34 23ZM54 23L54 24L53 24L53 25L54 25L54 24L55 24L55 23ZM56 23L56 24L57 24L57 23ZM110 23L110 24L111 24L111 23ZM112 23L112 25L113 25L113 23ZM6 24L6 25L7 25L7 24ZM51 24L51 25L52 25L52 24ZM88 24L88 25L89 25L89 24ZM95 24L95 25L96 25L96 24ZM11 25L11 26L12 26L12 28L14 28L14 27L13 27L13 26L12 26L12 25ZM55 25L55 26L52 26L52 27L51 27L51 28L52 28L52 29L51 29L51 30L52 30L52 31L55 31L55 32L56 32L56 31L55 31L55 29L56 29L56 25ZM57 25L57 27L58 27L58 25ZM59 25L59 27L60 27L60 28L61 28L61 30L62 30L62 32L63 32L63 34L62 34L62 33L61 33L61 34L62 34L62 35L63 35L63 37L62 37L62 36L61 36L61 35L60 35L60 36L61 36L61 37L59 37L59 35L57 35L57 37L59 37L59 40L60 40L60 39L61 39L61 41L62 41L62 42L64 42L64 43L61 43L61 42L60 42L60 41L58 41L58 39L57 39L57 40L56 40L56 41L57 41L57 44L58 44L58 42L60 42L60 43L59 43L59 46L61 46L61 47L59 47L59 48L61 48L61 47L63 47L63 48L64 48L64 47L67 47L67 45L68 45L68 44L67 44L67 42L68 42L68 41L67 41L67 40L66 40L66 39L68 39L68 40L69 40L69 41L70 41L70 40L69 40L69 39L70 39L70 36L69 36L69 35L70 35L70 34L69 34L69 33L70 33L70 30L69 30L69 31L68 31L68 32L69 32L69 33L68 33L68 37L67 37L67 34L66 34L66 35L65 35L65 33L66 33L66 31L67 31L67 30L66 30L66 29L64 29L64 30L62 30L62 28L61 28L61 27L63 27L63 28L64 28L64 27L63 27L63 26L60 26L60 25ZM75 25L75 27L76 27L76 28L77 28L77 27L76 27L76 25ZM81 25L81 26L82 26L82 25ZM38 26L38 27L39 27L39 29L40 29L40 27L39 27L39 26ZM71 26L71 27L69 27L69 28L71 28L71 27L72 27L72 26ZM86 26L86 27L87 27L87 28L88 28L88 27L87 27L87 26ZM113 26L113 27L114 27L114 29L116 29L116 28L115 28L115 27L114 27L114 26ZM52 27L52 28L53 28L53 29L52 29L52 30L54 30L54 27ZM105 27L105 28L106 28L106 27ZM47 29L47 30L48 30L48 29ZM57 29L57 30L58 30L58 29ZM59 29L59 30L60 30L60 29ZM90 29L90 30L91 30L91 29ZM92 29L92 31L91 31L91 33L89 33L89 34L91 34L91 35L93 35L93 34L91 34L91 33L92 33L92 31L93 31L93 30L94 30L94 29ZM13 30L13 31L14 31L14 30ZM16 30L16 31L17 31L17 33L18 33L18 32L19 32L19 31L20 31L20 32L21 32L21 31L20 31L20 30L19 30L19 31L17 31L17 30ZM65 30L65 31L64 31L64 32L65 32L65 31L66 31L66 30ZM71 30L71 32L73 32L73 31L72 31L72 30ZM5 31L5 34L8 34L8 31ZM26 31L26 33L27 33L27 35L28 35L28 33L27 33L27 31ZM31 31L31 34L34 34L34 31ZM57 31L57 34L60 34L60 31ZM83 31L83 34L86 34L86 31ZM109 31L109 34L112 34L112 31ZM6 32L6 33L7 33L7 32ZM32 32L32 33L33 33L33 32ZM58 32L58 33L59 33L59 32ZM84 32L84 33L85 33L85 32ZM110 32L110 33L111 33L111 32ZM37 33L37 36L38 36L38 37L40 37L40 38L39 38L39 39L40 39L40 40L41 40L41 39L40 39L40 38L42 38L42 36L41 36L41 35L39 35L39 36L38 36L38 34L39 34L39 33ZM80 33L80 34L81 34L81 33ZM73 34L73 35L74 35L74 34ZM64 35L64 36L65 36L65 35ZM83 35L83 36L85 36L85 39L86 39L86 38L87 38L87 37L86 37L86 36L88 36L88 35L86 35L86 36L85 36L85 35ZM100 35L100 36L99 36L99 37L100 37L100 36L101 36L101 35ZM111 35L111 36L112 36L112 35ZM1 36L1 37L0 37L0 38L1 38L1 37L2 37L2 36ZM6 36L6 37L7 37L7 36ZM26 36L26 38L25 38L25 37L24 37L24 39L22 39L22 40L23 40L23 41L24 41L24 39L26 39L26 38L27 38L27 37L28 37L28 36ZM79 36L79 37L80 37L80 36ZM64 37L64 38L63 38L63 39L62 39L62 38L61 38L61 39L62 39L62 40L63 40L63 39L64 39L64 42L65 42L65 39L66 39L66 38L67 38L67 37L66 37L66 38L65 38L65 37ZM68 37L68 39L69 39L69 37ZM83 37L83 38L82 38L82 40L83 40L83 39L84 39L84 37ZM88 37L88 38L89 38L89 37ZM106 37L106 38L104 38L104 40L106 40L106 41L107 41L107 39L108 39L108 40L109 40L109 39L108 39L108 37ZM20 38L20 40L19 40L19 42L17 42L17 43L16 43L16 44L17 44L17 45L18 45L18 44L19 44L19 43L21 43L21 42L22 42L22 41L21 41L21 42L20 42L20 40L21 40L21 38ZM106 38L106 39L107 39L107 38ZM4 39L4 40L5 40L5 39ZM13 39L13 40L14 40L14 39ZM90 39L90 40L89 40L89 41L90 41L90 42L91 42L91 41L92 41L92 42L94 42L94 47L98 47L98 48L97 48L97 49L98 49L98 48L99 48L99 47L98 47L98 46L95 46L95 44L96 44L96 45L97 45L97 44L96 44L96 43L95 43L95 42L94 42L94 40L93 40L93 41L92 41L92 39ZM53 40L53 42L52 42L52 43L51 43L51 42L50 42L50 44L49 44L49 45L48 45L48 46L50 46L50 47L51 47L51 48L52 48L52 47L57 47L57 48L58 48L58 45L57 45L57 46L56 46L56 45L55 45L55 46L53 46L53 45L54 45L54 44L56 44L56 42L55 42L55 40ZM4 41L4 42L5 42L5 41ZM8 41L8 42L9 42L9 41ZM66 41L66 42L67 42L67 41ZM113 41L113 42L112 42L112 43L113 43L113 42L114 42L114 45L115 45L115 46L116 46L116 45L115 45L115 44L116 44L116 43L115 43L115 41ZM46 42L46 43L47 43L47 42ZM17 43L17 44L18 44L18 43ZM52 43L52 44L51 44L51 45L53 45L53 44L54 44L54 43ZM60 43L60 45L61 45L61 46L62 46L62 44L61 44L61 43ZM64 43L64 44L65 44L65 45L63 45L63 47L64 47L64 46L66 46L66 45L67 45L67 44L65 44L65 43ZM81 43L81 44L82 44L82 43ZM27 44L27 45L28 45L28 46L27 46L27 47L26 47L26 48L25 48L25 47L24 47L24 46L23 46L23 48L25 48L25 49L26 49L26 50L25 50L25 53L26 53L26 52L27 52L27 51L26 51L26 50L27 50L27 49L29 49L29 47L28 47L28 46L29 46L29 45L28 45L28 44ZM78 44L78 45L79 45L79 44ZM98 44L98 45L99 45L99 44ZM38 45L38 47L39 47L39 48L38 48L38 49L39 49L39 50L38 50L38 51L39 51L39 52L40 52L40 51L43 51L43 52L42 52L42 53L40 53L40 54L39 54L39 55L41 55L41 56L40 56L40 59L39 59L39 61L40 61L40 63L39 63L39 62L38 62L38 65L39 65L39 66L38 66L38 67L33 67L33 68L34 68L34 69L35 69L35 70L36 70L36 71L34 71L34 74L36 74L36 75L35 75L35 76L37 76L37 74L38 74L38 76L39 76L39 75L41 75L41 76L40 76L40 77L41 77L41 78L42 78L42 77L43 77L43 75L44 75L44 74L45 74L45 73L46 73L46 74L48 74L48 73L49 73L49 74L50 74L50 75L51 75L51 76L52 76L52 74L54 74L54 75L53 75L53 76L54 76L54 75L55 75L55 73L56 73L56 75L57 75L57 77L59 77L59 76L60 76L60 75L63 75L63 76L64 76L64 77L68 77L68 76L64 76L64 74L65 74L65 75L68 75L68 73L70 73L70 74L71 74L71 75L72 75L72 74L73 74L73 73L74 73L74 72L76 72L76 71L77 71L77 72L78 72L78 71L79 71L79 70L78 70L78 69L77 69L77 70L74 70L74 68L76 68L76 67L73 67L73 65L74 65L74 66L76 66L76 65L78 65L78 64L76 64L76 63L75 63L75 62L77 62L77 63L79 63L79 62L77 62L77 60L76 60L76 59L77 59L77 58L76 58L76 57L78 57L78 55L79 55L79 54L80 54L80 53L79 53L79 54L78 54L78 53L77 53L77 52L76 52L76 53L75 53L75 54L74 54L74 55L72 55L72 54L71 54L71 55L70 55L70 53L71 53L71 52L73 52L73 53L74 53L74 52L73 52L73 51L72 51L72 50L71 50L71 48L70 48L70 46L68 46L68 49L70 49L70 50L69 50L69 51L67 51L67 50L65 50L65 49L64 49L64 50L62 50L62 51L61 51L61 49L60 49L60 51L59 51L59 52L60 52L60 51L61 51L61 53L60 53L60 56L61 56L61 54L62 54L62 55L63 55L63 56L62 56L62 57L61 57L61 58L62 58L62 60L64 60L64 58L62 58L62 57L64 57L64 56L65 56L65 55L66 55L66 56L67 56L67 57L68 57L68 56L70 56L70 58L65 58L65 62L63 62L63 61L61 61L61 62L59 62L59 61L58 61L58 62L56 62L56 63L58 63L58 65L56 65L56 64L55 64L55 62L53 62L53 61L54 61L54 60L56 60L56 58L54 58L54 57L55 57L55 56L56 56L56 54L57 54L57 56L58 56L58 53L57 53L57 52L56 52L56 54L54 54L54 53L55 53L55 50L56 50L56 49L53 49L53 51L54 51L54 53L53 53L53 52L52 52L52 50L49 50L49 47L47 47L47 48L45 48L45 47L46 47L46 46L44 46L44 45L43 45L43 46L42 46L42 47L41 47L41 46L40 46L40 47L39 47L39 45ZM100 45L100 46L101 46L101 45ZM51 46L51 47L52 47L52 46ZM74 46L74 47L75 47L75 49L76 49L76 47L75 47L75 46ZM89 46L89 47L91 47L91 46ZM6 47L6 48L7 48L7 47ZM16 47L16 48L13 48L13 49L12 49L12 50L13 50L13 51L12 51L12 53L10 53L10 54L12 54L12 53L13 53L13 54L14 54L14 53L13 53L13 51L14 51L14 50L13 50L13 49L17 49L17 50L15 50L15 52L16 52L16 53L15 53L15 54L16 54L16 53L17 53L17 55L16 55L16 56L19 56L19 57L17 57L17 58L16 58L16 60L15 60L15 61L16 61L16 60L17 60L17 58L19 58L19 57L20 57L20 55L18 55L18 53L20 53L20 52L17 52L17 50L18 50L18 51L20 51L20 50L18 50L18 48L17 48L17 47ZM27 47L27 48L28 48L28 47ZM34 47L34 49L33 49L33 50L35 50L35 47ZM40 47L40 48L39 48L39 49L40 49L40 50L39 50L39 51L40 51L40 50L41 50L41 49L40 49L40 48L41 48L41 47ZM43 47L43 48L42 48L42 49L44 49L44 50L43 50L43 51L44 51L44 50L46 50L46 52L47 52L47 51L48 51L48 48L47 48L47 49L45 49L45 48L44 48L44 47ZM57 49L57 51L58 51L58 50L59 50L59 49ZM93 49L93 51L95 51L95 50L94 50L94 49ZM9 50L9 51L8 51L8 52L7 52L7 53L8 53L8 52L9 52L9 51L10 51L10 52L11 52L11 51L10 51L10 50ZM21 50L21 51L22 51L22 50ZM70 50L70 51L69 51L69 52L68 52L68 54L67 54L67 52L66 52L66 53L65 53L65 51L63 51L63 52L62 52L62 53L63 53L63 52L64 52L64 54L63 54L63 55L65 55L65 54L67 54L67 56L68 56L68 54L69 54L69 52L70 52L70 51L71 51L71 50ZM49 51L49 52L50 52L50 54L49 54L49 53L47 53L47 54L46 54L46 56L47 56L47 58L46 58L46 59L45 59L45 56L44 56L44 55L45 55L45 52L44 52L44 53L43 53L43 54L44 54L44 55L42 55L42 56L41 56L41 58L42 58L42 57L43 57L43 59L42 59L42 61L41 61L41 59L40 59L40 61L41 61L41 62L43 62L43 64L44 64L44 63L47 63L47 64L46 64L46 66L45 66L45 67L50 67L50 68L51 68L51 69L50 69L50 70L49 70L49 68L46 68L46 69L45 69L45 68L44 68L44 65L42 65L42 63L40 63L40 66L39 66L39 67L38 67L38 68L35 68L35 69L36 69L36 70L37 70L37 71L38 71L38 70L39 70L39 69L38 69L38 68L39 68L39 67L40 67L40 68L41 68L41 69L40 69L40 72L42 72L42 71L41 71L41 69L43 69L43 71L45 71L45 72L43 72L43 73L41 73L41 74L44 74L44 73L45 73L45 72L46 72L46 73L47 73L47 70L46 70L46 69L48 69L48 71L50 71L50 73L51 73L51 74L52 74L52 73L55 73L55 72L56 72L56 73L57 73L57 74L58 74L58 75L60 75L60 74L63 74L63 73L64 73L64 72L65 72L65 71L66 71L66 72L68 72L68 70L70 70L70 71L69 71L69 72L70 72L70 71L72 71L72 72L73 72L73 71L74 71L74 70L73 70L73 69L72 69L72 70L71 70L71 69L69 69L69 68L68 68L68 67L67 67L67 68L66 68L66 67L62 67L62 66L63 66L63 65L64 65L64 66L66 66L66 65L64 65L64 64L65 64L65 63L64 63L64 64L63 64L63 63L62 63L62 62L61 62L61 63L59 63L59 62L58 62L58 63L59 63L59 64L60 64L60 65L58 65L58 66L57 66L57 67L56 67L56 65L54 65L54 64L53 64L53 63L52 63L52 62L51 62L51 61L52 61L52 60L53 60L53 59L51 59L51 61L50 61L50 60L49 60L49 59L50 59L50 57L51 57L51 58L53 58L53 54L52 54L52 53L51 53L51 51ZM82 51L82 52L81 52L81 54L82 54L82 53L83 53L83 51ZM84 51L84 52L85 52L85 51ZM88 51L88 52L89 52L89 51ZM91 51L91 52L92 52L92 51ZM103 51L103 52L101 52L101 53L105 53L105 51ZM23 52L23 53L24 53L24 52ZM34 52L34 53L35 53L35 52ZM107 52L107 53L108 53L108 52ZM50 54L50 55L47 55L47 56L48 56L48 57L49 57L49 56L52 56L52 55L51 55L51 54ZM75 54L75 55L74 55L74 57L73 57L73 56L71 56L71 58L70 58L70 59L69 59L69 61L68 61L68 62L67 62L67 60L68 60L68 59L67 59L67 60L66 60L66 62L67 62L67 63L66 63L66 64L67 64L67 63L68 63L68 62L70 62L70 63L69 63L69 64L68 64L68 65L67 65L67 66L69 66L69 64L70 64L70 67L71 67L71 64L70 64L70 63L72 63L72 62L75 62L75 61L74 61L74 58L75 58L75 57L76 57L76 56L77 56L77 54ZM75 55L75 56L76 56L76 55ZM95 55L95 56L97 56L97 55ZM43 56L43 57L44 57L44 56ZM0 57L0 58L1 58L1 57ZM5 57L5 60L8 60L8 57ZM31 57L31 60L34 60L34 57ZM57 57L57 60L60 60L60 57ZM72 57L72 58L71 58L71 61L73 61L73 59L72 59L72 58L73 58L73 57ZM83 57L83 60L86 60L86 57ZM109 57L109 60L112 60L112 57ZM114 57L114 58L113 58L113 60L115 60L115 62L116 62L116 60L115 60L115 59L114 59L114 58L116 58L116 57ZM6 58L6 59L7 59L7 58ZM32 58L32 59L33 59L33 58ZM48 58L48 59L46 59L46 60L45 60L45 59L44 59L44 60L45 60L45 61L44 61L44 62L46 62L46 60L47 60L47 61L48 61L48 62L47 62L47 63L48 63L48 64L47 64L47 65L48 65L48 64L49 64L49 65L50 65L50 67L52 67L52 66L53 66L53 65L52 65L52 63L51 63L51 62L50 62L50 63L51 63L51 65L50 65L50 64L49 64L49 63L48 63L48 62L49 62L49 60L48 60L48 59L49 59L49 58ZM58 58L58 59L59 59L59 58ZM84 58L84 59L85 59L85 58ZM110 58L110 59L111 59L111 58ZM96 60L96 63L98 63L98 62L97 62L97 61L98 61L98 60ZM19 61L19 62L20 62L20 61ZM25 61L25 62L26 62L26 61ZM9 63L9 64L10 64L10 63ZM21 63L21 64L22 64L22 63ZM61 63L61 65L63 65L63 64L62 64L62 63ZM73 63L73 64L72 64L72 65L73 65L73 64L75 64L75 65L76 65L76 64L75 64L75 63ZM114 63L114 65L115 65L115 63ZM19 64L19 65L20 65L20 64ZM29 64L29 65L30 65L30 64ZM98 65L98 67L96 67L96 68L99 68L99 70L98 70L98 69L96 69L96 70L97 70L97 71L95 71L95 73L96 73L96 72L97 72L97 74L96 74L96 75L95 75L95 76L98 76L98 75L99 75L99 74L98 74L98 72L97 72L97 71L101 71L101 70L102 70L102 69L101 69L101 68L100 68L100 66L99 66L99 65ZM4 66L4 67L3 67L3 69L4 69L4 68L6 68L6 67L7 67L7 66ZM24 66L24 67L26 67L26 66ZM41 66L41 67L43 67L43 66ZM54 66L54 67L53 67L53 68L52 68L52 69L51 69L51 70L50 70L50 71L51 71L51 72L52 72L52 71L53 71L53 72L55 72L55 71L56 71L56 72L57 72L57 71L58 71L58 73L59 73L59 74L60 74L60 73L62 73L62 72L64 72L64 70L66 70L66 71L67 71L67 70L68 70L68 68L67 68L67 69L66 69L66 68L64 68L64 70L62 70L62 68L60 68L60 69L61 69L61 70L59 70L59 68L58 68L58 67L57 67L57 68L55 68L55 70L54 70L54 71L53 71L53 69L54 69L54 67L55 67L55 66ZM59 66L59 67L61 67L61 66ZM105 66L105 67L106 67L106 66ZM79 67L79 68L80 68L80 67ZM84 67L84 68L85 68L85 71L84 71L84 72L85 72L85 73L84 73L84 74L85 74L85 75L86 75L86 74L85 74L85 73L86 73L86 72L85 72L85 71L87 71L87 72L88 72L88 71L87 71L87 70L86 70L86 68L85 68L85 67ZM87 67L87 69L88 69L88 68L89 68L89 67ZM57 68L57 69L58 69L58 68ZM93 68L93 69L94 69L94 68ZM116 68L116 69L115 69L115 71L116 71L116 70L117 70L117 68ZM44 69L44 70L45 70L45 71L46 71L46 70L45 70L45 69ZM6 70L6 71L7 71L7 72L8 72L8 73L7 73L7 74L6 74L6 75L7 75L7 74L9 74L9 72L8 72L8 71L7 71L7 70ZM11 70L11 72L12 72L12 70ZM32 70L32 71L33 71L33 70ZM51 70L51 71L52 71L52 70ZM58 70L58 71L59 71L59 70ZM61 70L61 71L60 71L60 72L61 72L61 71L62 71L62 70ZM72 70L72 71L73 71L73 70ZM77 70L77 71L78 71L78 70ZM25 71L25 75L27 75L27 74L26 74L26 71ZM28 72L28 73L29 73L29 75L30 75L30 76L31 76L31 75L30 75L30 73L29 73L29 72ZM32 72L32 73L31 73L31 74L32 74L32 73L33 73L33 72ZM35 72L35 73L36 73L36 74L37 74L37 73L36 73L36 72ZM38 72L38 73L39 73L39 72ZM116 72L116 73L117 73L117 72ZM1 73L1 74L2 74L2 73ZM71 73L71 74L72 74L72 73ZM97 74L97 75L98 75L98 74ZM109 74L109 75L110 75L110 74ZM113 74L113 76L115 76L115 77L116 77L116 75L114 75L114 74ZM80 75L80 76L82 76L82 75ZM88 75L88 77L89 77L89 78L90 78L90 77L91 77L91 76L89 76L89 75ZM105 75L105 76L106 76L106 75ZM14 76L14 77L17 77L17 78L16 78L16 79L15 79L15 78L14 78L14 79L15 79L15 82L16 82L16 83L19 83L19 82L18 82L18 81L20 81L20 79L19 79L19 77L17 77L17 76ZM55 76L55 77L56 77L56 76ZM107 76L107 78L109 78L109 76ZM4 77L4 78L5 78L5 77ZM79 77L79 78L77 78L77 80L79 80L79 78L80 78L80 77ZM17 78L17 79L16 79L16 81L17 81L17 79L18 79L18 80L19 80L19 79L18 79L18 78ZM25 78L25 79L24 79L24 80L25 80L25 81L26 81L26 80L27 80L27 81L28 81L28 80L29 80L29 79L26 79L26 78ZM60 78L60 79L61 79L61 78ZM92 78L92 79L93 79L93 81L94 81L94 79L95 79L95 78L94 78L94 79L93 79L93 78ZM96 78L96 79L97 79L97 80L96 80L96 81L99 81L99 79L97 79L97 78ZM3 79L3 80L2 80L2 82L1 82L1 83L2 83L2 82L4 82L4 81L3 81L3 80L4 80L4 79ZM8 79L8 80L9 80L9 79ZM25 79L25 80L26 80L26 79ZM41 79L41 80L42 80L42 79ZM80 79L80 81L81 81L81 80L82 80L82 79ZM6 80L6 81L7 81L7 80ZM67 80L67 82L66 82L66 83L65 83L65 82L63 82L63 83L62 83L62 84L63 84L63 83L64 83L64 88L61 88L61 89L62 89L62 90L63 90L63 91L64 91L64 88L65 88L65 89L67 89L67 88L68 88L68 90L69 90L69 88L68 88L68 87L69 87L69 86L70 86L70 89L71 89L71 90L70 90L70 93L71 93L71 94L70 94L70 95L71 95L71 94L73 94L73 93L74 93L74 94L75 94L75 93L77 93L77 92L78 92L78 93L79 93L79 97L80 97L80 98L81 98L81 97L80 97L80 95L81 95L81 96L82 96L82 95L83 95L83 93L84 93L84 92L82 92L82 91L81 91L81 92L82 92L82 94L81 94L81 93L79 93L79 92L80 92L80 90L79 90L79 92L78 92L78 91L76 91L76 92L75 92L75 93L74 93L74 92L72 92L72 93L71 93L71 90L73 90L73 91L74 91L74 89L75 89L75 88L74 88L74 89L71 89L71 86L72 86L72 87L74 87L74 86L75 86L75 87L76 87L76 88L77 88L77 89L76 89L76 90L77 90L77 89L80 89L80 88L81 88L81 90L83 90L83 89L84 89L84 90L85 90L85 88L82 88L82 87L79 87L79 88L77 88L77 86L78 86L78 85L79 85L79 86L80 86L80 85L81 85L81 84L80 84L80 83L78 83L78 84L77 84L77 83L74 83L74 81L72 81L72 80L71 80L71 81L70 81L70 80L69 80L69 81L70 81L70 82L68 82L68 80ZM71 81L71 82L72 82L72 81ZM102 81L102 82L101 82L101 83L103 83L103 82L104 82L104 81ZM37 82L37 83L36 83L36 84L37 84L37 83L38 83L38 82ZM39 82L39 83L40 83L40 82ZM94 82L94 83L95 83L95 84L96 84L96 85L94 85L94 86L93 86L93 87L95 87L95 86L97 86L97 87L98 87L98 86L99 86L99 85L100 85L100 86L102 86L102 85L100 85L100 84L99 84L99 83L98 83L98 82L96 82L96 83L95 83L95 82ZM5 83L5 86L8 86L8 83ZM31 83L31 86L34 86L34 83ZM57 83L57 86L60 86L60 83ZM66 83L66 84L65 84L65 85L66 85L66 84L70 84L70 85L72 85L72 84L74 84L74 83ZM83 83L83 86L86 86L86 83ZM96 83L96 84L97 84L97 86L98 86L98 84L97 84L97 83ZM109 83L109 86L112 86L112 83ZM3 84L3 85L4 85L4 84ZM6 84L6 85L7 85L7 84ZM9 84L9 85L11 85L11 86L9 86L9 87L6 87L6 88L7 88L7 89L5 89L5 88L4 88L4 87L2 87L2 86L1 86L1 88L4 88L4 89L5 89L5 92L7 92L7 91L6 91L6 90L7 90L7 89L8 89L8 90L9 90L9 89L10 89L10 88L12 88L12 87L11 87L11 86L13 86L13 85L12 85L12 84ZM32 84L32 85L33 85L33 84ZM58 84L58 85L59 85L59 84ZM75 84L75 85L76 85L76 84ZM84 84L84 85L85 85L85 84ZM110 84L110 85L111 85L111 84ZM24 85L24 87L26 87L26 85ZM62 85L62 86L63 86L63 85ZM67 85L67 86L65 86L65 88L67 88L67 87L68 87L68 85ZM73 85L73 86L74 86L74 85ZM113 85L113 86L114 86L114 85ZM53 86L53 87L52 87L52 88L51 88L51 90L52 90L52 92L53 92L53 91L54 91L54 92L55 92L55 91L54 91L54 89L53 89L53 90L52 90L52 88L53 88L53 87L54 87L54 86ZM9 87L9 88L10 88L10 87ZM14 87L14 88L15 88L15 87ZM37 87L37 88L35 88L35 89L37 89L37 91L38 91L38 88L39 88L39 87ZM91 87L91 88L89 88L89 89L90 89L90 90L91 90L91 91L90 91L90 92L91 92L91 94L92 94L92 95L93 95L93 94L92 94L92 92L91 92L91 91L93 91L93 90L94 90L94 92L95 92L95 91L96 91L96 89L95 89L95 88L94 88L94 89L93 89L93 88L92 88L92 87ZM107 87L107 88L106 88L106 90L107 90L107 89L108 89L108 90L109 90L109 89L110 89L110 88L109 88L109 89L108 89L108 87ZM115 87L115 89L113 89L113 90L115 90L115 89L117 89L117 88L116 88L116 87ZM12 89L12 90L13 90L13 89ZM57 89L57 90L58 90L58 89ZM92 89L92 90L93 90L93 89ZM94 89L94 90L95 90L95 89ZM0 90L0 92L1 92L1 90ZM35 90L35 91L36 91L36 90ZM43 90L43 92L44 92L44 94L45 94L45 92L44 92L44 90ZM110 90L110 91L111 91L111 90ZM10 91L10 93L9 93L9 94L10 94L10 93L11 93L11 91ZM28 91L28 92L27 92L27 93L30 93L30 91ZM50 91L50 92L49 92L49 93L51 93L51 91ZM103 91L103 92L104 92L104 93L107 93L107 92L104 92L104 91ZM115 91L115 93L114 93L114 94L112 94L112 96L110 96L110 98L112 98L112 100L113 100L113 101L114 101L114 102L113 102L113 103L112 103L112 104L111 104L111 105L109 105L109 103L110 103L110 102L112 102L112 101L108 101L108 105L107 105L107 106L106 106L106 107L107 107L107 106L112 106L112 104L113 104L113 105L116 105L116 102L117 102L117 99L116 99L116 98L117 98L117 97L116 97L116 95L115 95L115 93L116 93L116 92L117 92L117 91ZM60 92L60 93L61 93L61 92ZM85 92L85 93L86 93L86 94L85 94L85 95L84 95L84 97L85 97L85 95L87 95L87 92ZM110 92L110 93L111 93L111 92ZM64 93L64 94L62 94L62 95L61 95L61 96L62 96L62 95L63 95L63 96L64 96L64 94L65 94L65 95L67 95L67 93ZM27 94L27 95L28 95L28 98L31 98L31 99L32 99L32 97L29 97L29 96L30 96L30 95L28 95L28 94ZM40 94L40 96L41 96L41 97L40 97L40 98L43 98L43 96L44 96L44 98L45 98L45 96L44 96L44 95L43 95L43 94L42 94L42 95L41 95L41 94ZM49 94L49 95L50 95L50 94ZM108 94L108 95L109 95L109 94ZM6 95L6 96L4 96L4 97L3 97L3 98L4 98L4 97L6 97L6 98L5 98L5 99L4 99L4 100L8 100L8 98L9 98L9 97L8 97L8 98L7 98L7 97L6 97L6 96L8 96L8 95ZM42 95L42 96L43 96L43 95ZM99 95L99 96L98 96L98 99L99 99L99 98L100 98L100 97L101 97L101 96L100 96L100 95ZM113 95L113 96L112 96L112 98L113 98L113 96L114 96L114 97L115 97L115 98L114 98L114 99L113 99L113 100L115 100L115 102L116 102L116 100L115 100L115 98L116 98L116 97L115 97L115 95ZM6 98L6 99L7 99L7 98ZM24 98L24 99L23 99L23 101L24 101L24 100L25 100L25 98ZM73 98L73 99L74 99L74 98ZM89 99L89 100L90 100L90 101L91 101L91 100L90 100L90 99ZM94 100L94 101L95 101L95 100ZM13 101L13 103L15 103L15 104L16 104L16 103L15 103L15 101ZM26 101L26 104L27 104L27 105L28 105L28 104L29 104L29 103L27 103L27 101ZM62 101L62 103L64 103L64 102L65 102L65 104L67 104L67 106L68 106L68 104L67 104L67 102L65 102L65 101ZM6 102L6 103L8 103L8 102ZM32 102L32 105L33 105L33 104L34 104L34 103L33 103L33 102ZM43 102L43 103L45 103L45 102ZM48 102L48 103L49 103L49 102ZM93 102L93 103L92 103L92 104L93 104L93 105L94 105L94 104L95 104L95 103L96 103L96 102L95 102L95 103L94 103L94 102ZM93 103L93 104L94 104L94 103ZM99 103L99 105L100 105L100 103ZM113 103L113 104L115 104L115 103ZM9 104L9 106L11 106L11 105L10 105L10 104ZM6 105L6 106L7 106L7 105ZM35 105L35 106L36 106L36 107L38 107L38 106L37 106L37 105ZM60 105L60 106L61 106L61 105ZM96 105L96 106L97 106L97 107L98 107L98 106L97 106L97 105ZM102 105L102 106L103 106L103 105ZM76 106L76 110L77 110L77 111L78 111L78 110L77 110L77 108L78 108L78 109L79 109L79 108L80 108L80 107L79 107L79 106L78 106L78 107L77 107L77 106ZM14 107L14 108L15 108L15 107ZM46 107L46 108L47 108L47 107ZM78 107L78 108L79 108L79 107ZM95 108L95 109L96 109L96 110L97 110L97 111L98 111L98 110L99 110L99 109L100 109L100 108L99 108L99 109L98 109L98 110L97 110L97 108ZM12 109L12 110L13 110L13 109ZM19 109L19 111L18 111L18 112L19 112L19 111L20 111L20 113L21 113L21 112L22 112L22 111L21 111L21 110L20 110L20 109ZM28 109L28 111L30 111L30 110L29 110L29 109ZM31 109L31 112L34 112L34 109ZM35 109L35 111L36 111L36 112L38 112L38 111L36 111L36 110L37 110L37 109ZM43 109L43 110L44 110L44 109ZM46 109L46 110L47 110L47 111L48 111L48 109ZM57 109L57 112L60 112L60 109ZM83 109L83 112L86 112L86 109ZM109 109L109 112L112 112L112 109ZM32 110L32 111L33 111L33 110ZM54 110L54 111L56 111L56 110ZM58 110L58 111L59 111L59 110ZM84 110L84 111L85 111L85 110ZM94 110L94 112L92 112L92 113L91 113L91 114L92 114L92 113L93 113L93 114L94 114L94 112L95 112L95 110ZM100 110L100 112L98 112L98 113L99 113L99 114L100 114L100 113L101 113L101 112L102 112L102 113L104 113L104 112L102 112L102 111L101 111L101 110ZM110 110L110 111L111 111L111 110ZM67 111L67 113L68 113L68 112L69 112L69 111ZM105 111L105 112L106 112L106 111ZM15 112L15 113L16 113L16 114L17 114L17 113L16 113L16 112ZM55 112L55 113L56 113L56 112ZM57 113L57 115L58 115L58 113ZM110 113L110 114L107 114L107 115L110 115L110 116L111 116L111 115L112 115L112 114L111 114L111 113ZM27 114L27 115L28 115L28 116L26 116L26 117L28 117L28 116L31 116L31 115L33 115L33 116L34 116L34 115L35 115L35 114L34 114L34 115L33 115L33 114L31 114L31 115L29 115L29 114ZM67 114L67 115L66 115L66 116L67 116L67 117L69 117L69 116L67 116L67 115L69 115L69 114ZM96 114L96 115L95 115L95 116L97 116L97 114ZM8 115L8 117L15 117L15 116L14 116L14 115L11 115L11 116L9 116L9 115ZM17 115L17 116L18 116L18 115ZM73 115L73 117L74 117L74 115ZM24 116L24 117L25 117L25 116ZM42 116L42 117L43 117L43 116ZM45 116L45 117L47 117L47 116ZM52 116L52 117L54 117L54 116ZM82 116L82 117L83 117L83 116ZM113 116L113 117L115 117L115 116ZM116 116L116 117L117 117L117 116ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM117 0L110 0L110 7L117 7ZM116 1L111 1L111 6L116 6ZM115 2L112 2L112 5L115 5ZM0 117L7 117L7 110L0 110ZM1 116L6 116L6 111L1 111ZM2 115L5 115L5 112L2 112Z\" fill=\"#000000\"/></g></g></svg>\n"
            ]
        }
    }
}
XXX;
        // Clean up common issues
        $payload = $this->fullySanitizeAndDecode($data);

        dd($payload);

//        return $ctx->er->toArray();
    }

    /**
     * Sanitize and decode a JSON string safely.
     *
     * @param  string  $raw
     * @param  bool    $assoc
     * @return array|null
     */
    protected function fullySanitizeAndDecode(string $raw): array|null
    {
        // Trim and remove BOM
        $clean = preg_replace('/^\xEF\xBB\xBF/', '', trim($raw));

        // Normalize newlines
        $clean = str_replace(["\r\n", "\r"], "\n", $clean);

        // Fix encoding if broken
        if (!mb_check_encoding($clean, 'UTF-8')) {
            $clean = mb_convert_encoding($clean, 'UTF-8', 'auto');
        }

        // Now decode using Laravel-style request
        return $this->decodeViaLaravelRequest($clean);
    }

    protected function decodeViaLaravelRequest(string $raw): array|null
    {
        $request = Request::create(
            uri: '/simulate',
            method: 'POST',
            content: $raw,
            server: [
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        dd(json5_decode($request->getContent(), true));
        try {
            return $request->json(); // Laravel's internal JSON decoder
        } catch (\Throwable $e) {
            logger()->warning('[decodeViaLaravelRequest] Laravel JSON decode failed', [
                'error' => $e->getMessage(),
                'snippet' => mb_substr($raw, 0, 500),
            ]);
            return null;
        }
    }
}
