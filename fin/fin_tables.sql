-- name_translation
-- date_translation
-- account_codes
-- core_rcr_budget_numbers
-- pca_code_date_corrected
-- pca_transaction_split
-- gfa_list
;

create table `load_all` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
TDPrimaryKey varchar(64) default null,
ProjectCode	varchar(64) default null,
TranAmount	int(11) default 0,
GFA varchar(64) default null,
AdjustmentLoadedDate varchar(64) default null,
BudgetNbr varchar(64) default null,
BudgetName varchar(64) default null,
AccountCode varchar(64) default null,
PCAProjectCodeOrig varchar(64) default null,
PCAProjectCodePosting varchar(64) default null,
Budget_Begin varchar(64) default null,
Budget_End varchar(64) default null,
TranDate1 varchar(64) default null,
TranDescMod varchar(64) default null,
TranReference2 varchar(64) default null,
TranReference4 varchar(64) default null,
Modified varchar(64) default null,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


drop table `GFA_List`;

create table `GFA_List` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
BudgetNbr varchar(64) default null,
GFA varchar(64) default null,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


drop table `tbl_DateTranslation`;

create table `tbl_DateTranslation` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
FiscalMonth int(11) default 0,
FiscalYear int(11) default 0,
ITECHMonth int(11) default 0,
ITECHYear int(11) default 0,
ActualMonth int(11) default 0,
ActualYear int(11) default 0,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

drop table `tbl_NameTranslation`;

create table `tbl_NameTranslation` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
tbl_TravelName varchar(64) default null,
tbl_DescName varchar(64) default null,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


drop table `pca_transaction_split`;

create table `pca_transaction_split` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
sub_TDPrimaryKey varchar(64) default null,
sub_ProjectCode	varchar(64) default null,
sub_TranDate varchar(64) default null,
sub_GFA	varchar(64) default null,
sub_ChangeDate varchar(64) default null,
sub_TranAmount int(11) default 0,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

drop table `pca_code_date_corrected`;

create table `pca_code_date_corrected` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
PCACode_TDPrimaryKey varchar(64) default null,
PCACode_ProjectCode	varchar(64) default null,
PCACode_TranAmount	int(11) default 0,
PCACode_Corrected_Date	varchar(64) default null,
PCACode_UserID	varchar(64) default null,
PCACode_Change_Date	varchar(64) default null,
PCACode_Notes	varchar(256) default null,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

drop table `budget_activity_detail`;

create table `budget_activity_detail` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
`DataSource` varchar(64) default null,
`BienniumYear` varchar(64) default null,
`FASRunNbr` varchar(64) default null,
`ProgramCategoryCode` varchar(64) default null,
`ProgramCategoryDesc` varchar(64) default null,
`BudgetNbr` varchar(64) default null,
`BudgetName` varchar(64) default null,
`AccountCode` varchar(64) default null,
`AccountName` varchar(64) default null,
`FundCode` varchar(64) default null,
`FundDesc` varchar(64) default null,
`AppropriationCode` varchar(64) default null,
`AppropriationDesc` varchar(64) default null,
`PCAStatusFlag` varchar(64) default null,
`PCAOrgCode` varchar(64) default null,
`PCAOrgName` varchar(64) default null,
`PCAProjectCodeOrig` varchar(64) default null,
`PCATaskCodeOrig` varchar(64) default null,
`PCAOptionCodeOrig` varchar(64) default null,
`PCAProjectCodePosting` varchar(64) default null,
`PCATaskCodePosting` varchar(64) default null,
`PCAOptionCodePosting` varchar(64) default null,
`PCAOvhdAllocationInd` varchar(64) default null,
`PCAOvhdAllocationDesc` varchar(64) default null,
`LaborDistribInclusionInd` varchar(64) default null,
`LaborDistribInclusionDesc` varchar(64) default null,
`PCAEditCode1` varchar(64) default null,
`PCAEditCode2` varchar(64) default null,
`PCAEditCode3` varchar(64) default null,
`PCAEditCode4` varchar(64) default null,
`PCAEditCode5` varchar(64) default null,
`PCAEditCode6` varchar(64) default null,
`OrgCode` varchar(64) default null,
`OrgName` varchar(64) default null,
`BudgetTypeCode` varchar(64) default null,
`BudgetTypeDesc` varchar(64) default null,
`BudgetClassCode` varchar(64) default null,
`BudgetClassDesc` varchar(64) default null,
`ProgramTypeCode` varchar(64) default null,
`ProgramTypeDesc` varchar(64) default null,
`AccountFlag1` varchar(64) default null,
`AccountFlagDesc1` varchar(64) default null,
`AccountFlag2` varchar(64) default null,
`AccountFlagDesc2` varchar(64) default null,
`AccountFlag3` varchar(64) default null,
`AccountFlagDesc3` varchar(64) default null,
`AccountFlag4` varchar(64) default null,
`AccountFlagDesc4` varchar(64) default null,
`AccountFlag5` varchar(64) default null,
`AccountFlagDesc5` varchar(64) default null,
`AccountFlag6` varchar(64) default null,
`AccountFlagDesc6` varchar(64) default null,
`AccountFlag7` varchar(64) default null,
`AccountFlagDesc7` varchar(64) default null,
`AccountFlag8` varchar(64) default null,
`AccountFlagDesc8` varchar(64) default null,
`AccountFlag9` varchar(64) default null,
`AccountFlagDesc9` varchar(64) default null,
`AccountFlag10` varchar(64) default null,
`AccountFlagDesc10` varchar(64) default null,
`GrantControlCode1` varchar(64) default null,
`GrantControlDesc1` varchar(64) default null,
`GrantControlCode2` varchar(64) default null,
`GrantControlDesc2` varchar(64) default null,
`GrantControlCode3` varchar(64) default null,
`GrantControlDesc3` varchar(64) default null,
`GrantControlCode4` varchar(64) default null,
`GrantControlDesc4` varchar(64) default null,
`GrantControlCode5` varchar(64) default null,
`GrantControlDesc5` varchar(64) default null,
`GrantControlCode6` varchar(64) default null,
`GrantControlDesc6` varchar(64) default null,
`GrantControlCode7` varchar(64) default null,
`GrantControlDesc7` varchar(64) default null,
`GrantControlCode8` varchar(64) default null,
`GrantControlDesc8` varchar(64) default null,
`GrantControlCode9` varchar(64) default null,
`GrantControlDesc9` varchar(64) default null,
`GrantControlCode10` varchar(64) default null,
`GrantControlDesc10` varchar(64) default null,
`StateObjectCode` varchar(64) default null,
`StateObjectDesc` varchar(64) default null,
`BudgetLevelAllotmentControlCode` varchar(64) default null,
`BudgetLevelAllotmentControlDesc` varchar(64) default null,
`TranEditMsgCode1` varchar(64) default null,
`TranEditMsgCode2` varchar(64) default null,
`TranCode` varchar(64) default null,
`TranCodeDesc` varchar(64) default null,
`TranDate1` varchar(64) default null,
`TranDate1Invalid` varchar(64) default null,
`AcctgMonth` varchar(64) default null,
`FiscalYear` varchar(64) default null,
`FiscalMonth` varchar(64) default null,
`FiscalYearAdjPeriodInd` varchar(64) default null,
`PriorPeriodInd` varchar(64) default null,
`OriginatingSystemCode` varchar(64) default null,
`OriginatingSystemName` varchar(64) default null,
`TranPostingDate` varchar(64) default null,
`TranAmount` float default 0,
`PositionNbr` varchar(64) default null,
`TranReference1` varchar(64) default null,
`TranReference2` varchar(64) default null,
`TranReference3` varchar(64) default null,
`TranReference4` varchar(64) default null,
`TranDesc` varchar(64) default null,
`TranAdditionalDesc` varchar(64) default null,
`TranDate2` varchar(64) default null,
`TranDate2Invalid` varchar(64) default null,
`TranDate3` varchar(64) default null,
`TranDate3Invalid` varchar(64) default null,
`TranFTE` float default 0,
`UnitsCode` varchar(64) default null,
`UnitsDesc` varchar(64) default null,
`TranRate` float default 0,
`TranQuantity` float default 0,
`FASExplosionGenerated` varchar(64) default null,
`EncumbranceLiqCode` varchar(64) default null,
`EncumbranceLiqDesc` varchar(64) default null,
`EncFundCode` varchar(64) default null,
`EncFundDesc` varchar(64) default null,
`EncRecordType` varchar(64) default null,
`EncNbr` varchar(64) default null,
`EncBudgetNbr` varchar(64) default null,
`EncBudgetName` varchar(64) default null,
`EncAccountCode` varchar(64) default null,
`EncAccountName` varchar(64) default null,
`EncProjectCode` varchar(64) default null,
`EncTaskCode` varchar(64) default null,
`EncOptionCode` varchar(64) default null,
`CurrentAmount` float default 0,
`FuturePeriodAmount` float default 0,
`CurrentPeriodFTE` float default 0,
`FuturePeriodFTE` float default 0,
`JobClassCode` varchar(64) default null,
`JobClass` varchar(64) default null,
`ServicePeriod` varchar(64) default null,
`ServicePeriodDesc` varchar(64) default null,
`EarnTypeCode` varchar(64) default null,
`EarnType` varchar(64) default null,
`ApptNbr` varchar(64) default null,
`DistNbr` varchar(64) default null,
`VendorNbr` varchar(64) default null,
`BankCode` varchar(64) default null,
`BankDesc` varchar(64) default null,
`CashFlag` varchar(64) default null,
`CheckFlag` varchar(64) default null,
`MoneyOrderFlag` varchar(64) default null,
`DocumentSequenceInvoiceNbr` varchar(64) default null,
`PaymentSequenceNbr` varchar(64) default null,
`CheckNbr` varchar(64) default null,
`OriginatingAreaCode` varchar(64) default null,
`OriginatingAreaDesc` varchar(64) default null,
`PCATranInd` varchar(64) default null,
`PCATranDesc` varchar(64) default null,
`PCATranTypeCode` varchar(64) default null,
`PCATranTypeDesc` varchar(64) default null,
`PCADebitReferenceNbr` varchar(64) default null,
`OFISInclusionInd` varchar(64) default null,
`OFISInclusionDesc` varchar(64) default null,
`HeppsJVInd` varchar(64) default null,
`HeppsJVDesc` varchar(64) default null,
`BudgetPermTempCarryFwdCode` varchar(64) default null,
`BudgetPermTempCarryFwdDesc` varchar(64) default null,
`BeginBudgetSpreadYear` varchar(64) default null,
`BeginBudgetSpreadMonth` varchar(64) default null,
`FirstYearBudgetAmount` float default 0,
`BudgetCurrentBienniumAmount` float default 0,
`BudgetCurrentMonths` int(11) default 0,
`BudgetFacAmount` float default 0,
`BudgetFacAmountYear1` float default 0,
`BudgetFacAmountYear2` float default 0,
`BudgetFTE` float default 0,
`BudgetFTEAmountYear1` float default 0,
`BudgetFTEAmountYear2` float default 0,
`BudgetFutureBeginSpreadYM` varchar(64) default null,
`BudgetFutureBienniumAmount` float default 0,
`BudgetFutureMonths` int(11) default 0,
`BudgetGrantAmount` float default 0,
`BudgetHistoryToDateAmount` float default 0,
`FacultyActivityCount` float default 0,
`CTIRequestServiceCode` varchar(64) default null,
`CTIRequestServiceDesc` varchar(64) default null,
`CTIProgramCategoryCode` varchar(64) default null,
`CTIProgramCategoryDesc` varchar(64) default null,
`CTIBudgetNbr` varchar(64) default null,
`CTIBudgetName` varchar(64) default null,
`CTIBudgetTypeCode` varchar(64) default null,
`CTIBudgetTypeDesc` varchar(64) default null,
`CTIAccountCode` varchar(64) default null,
`CTIAccountName` varchar(64) default null,
`CTIFundCode` varchar(64) default null,
`CTIFundDesc` varchar(64) default null,
`CTIAppropriationCode` varchar(64) default null,
`CTIAppropriationDesc` varchar(64) default null,
`EmployeeIDNbr` varchar(64) default null,
`ActualHistoryToDateAmount` float default 0,
`BankDepositNbr` varchar(64) default null,
`CommodityCode` varchar(64) default null,
`ContactPhoneNbr` varchar(64) default null,
`DocumentNbr` varchar(64) default null,
`EncAdjustmentNbrPurchasing` varchar(64) default null,
`PayeeName` varchar(64) default null,
`RequisitionNbr` varchar(64) default null,
`VendorInvoiceNbr` varchar(64) default null,
`TDPrimaryKey` varchar(64) default null,
`TranOriginalDerivedCode` varchar(64) default null,
`AppTranOriginating` varchar(64) default null,
`ReferenceDocumentId` varchar(64) default null,
`GCATransTypeCode` varchar(64) default null,
`StageLoadTimestamp` varchar(64) default null,
`ODSLoadTimestamp` varchar(64) default null,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


drop table `sec_BudgetActivityDetailCurrentBiennium`;

create table `sec_BudgetActivityDetailCurrentBiennium` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
`DataSource` varchar(64) default null,
`BienniumYear` varchar(64) default null,
`FASRunNbr` varchar(64) default null,
`ProgramCategoryCode` varchar(64) default null,
`ProgramCategoryDesc` varchar(64) default null,
`BudgetNbr` varchar(64) default null,
`BudgetName` varchar(64) default null,
`AccountCode` varchar(64) default null,
`AccountName` varchar(64) default null,
`FundCode` varchar(64) default null,
`FundDesc` varchar(64) default null,
`AppropriationCode` varchar(64) default null,
`AppropriationDesc` varchar(64) default null,
`PCAStatusFlag` varchar(64) default null,
`PCAOrgCode` varchar(64) default null,
`PCAOrgName` varchar(64) default null,
`PCAProjectCodeOrig` varchar(64) default null,
`PCATaskCodeOrig` varchar(64) default null,
`PCAOptionCodeOrig` varchar(64) default null,
`PCAProjectCodePosting` varchar(64) default null,
`PCATaskCodePosting` varchar(64) default null,
`PCAOptionCodePosting` varchar(64) default null,
`PCAOvhdAllocationInd` varchar(64) default null,
`PCAOvhdAllocationDesc` varchar(64) default null,
`LaborDistribInclusionInd` varchar(64) default null,
`LaborDistribInclusionDesc` varchar(64) default null,
`PCAEditCode1` varchar(64) default null,
`PCAEditCode2` varchar(64) default null,
`PCAEditCode3` varchar(64) default null,
`PCAEditCode4` varchar(64) default null,
`PCAEditCode5` varchar(64) default null,
`PCAEditCode6` varchar(64) default null,
`OrgCode` varchar(64) default null,
`OrgName` varchar(64) default null,
`BudgetTypeCode` varchar(64) default null,
`BudgetTypeDesc` varchar(64) default null,
`BudgetClassCode` varchar(64) default null,
`BudgetClassDesc` varchar(64) default null,
`ProgramTypeCode` varchar(64) default null,
`ProgramTypeDesc` varchar(64) default null,
`AccountFlag1` varchar(64) default null,
`AccountFlagDesc1` varchar(64) default null,
`AccountFlag2` varchar(64) default null,
`AccountFlagDesc2` varchar(64) default null,
`AccountFlag3` varchar(64) default null,
`AccountFlagDesc3` varchar(64) default null,
`AccountFlag4` varchar(64) default null,
`AccountFlagDesc4` varchar(64) default null,
`AccountFlag5` varchar(64) default null,
`AccountFlagDesc5` varchar(64) default null,
`AccountFlag6` varchar(64) default null,
`AccountFlagDesc6` varchar(64) default null,
`AccountFlag7` varchar(64) default null,
`AccountFlagDesc7` varchar(64) default null,
`AccountFlag8` varchar(64) default null,
`AccountFlagDesc8` varchar(64) default null,
`AccountFlag9` varchar(64) default null,
`AccountFlagDesc9` varchar(64) default null,
`AccountFlag10` varchar(64) default null,
`AccountFlagDesc10` varchar(64) default null,
`GrantControlCode1` varchar(64) default null,
`GrantControlDesc1` varchar(64) default null,
`GrantControlCode2` varchar(64) default null,
`GrantControlDesc2` varchar(64) default null,
`GrantControlCode3` varchar(64) default null,
`GrantControlDesc3` varchar(64) default null,
`GrantControlCode4` varchar(64) default null,
`GrantControlDesc4` varchar(64) default null,
`GrantControlCode5` varchar(64) default null,
`GrantControlDesc5` varchar(64) default null,
`GrantControlCode6` varchar(64) default null,
`GrantControlDesc6` varchar(64) default null,
`GrantControlCode7` varchar(64) default null,
`GrantControlDesc7` varchar(64) default null,
`GrantControlCode8` varchar(64) default null,
`GrantControlDesc8` varchar(64) default null,
`GrantControlCode9` varchar(64) default null,
`GrantControlDesc9` varchar(64) default null,
`GrantControlCode10` varchar(64) default null,
`GrantControlDesc10` varchar(64) default null,
`StateObjectCode` varchar(64) default null,
`StateObjectDesc` varchar(64) default null,
`BudgetLevelAllotmentControlCode` varchar(64) default null,
`BudgetLevelAllotmentControlDesc` varchar(64) default null,
`TranEditMsgCode1` varchar(64) default null,
`TranEditMsgCode2` varchar(64) default null,
`TranCode` varchar(64) default null,
`TranCodeDesc` varchar(64) default null,
`TranDate1` varchar(64) default null,
`TranDate1Invalid` varchar(64) default null,
`AcctgMonth` varchar(64) default null,
`FiscalYear` varchar(64) default null,
`FiscalMonth` varchar(64) default null,
`FiscalYearAdjPeriodInd` varchar(64) default null,
`PriorPeriodInd` varchar(64) default null,
`OriginatingSystemCode` varchar(64) default null,
`OriginatingSystemName` varchar(64) default null,
`TranPostingDate` varchar(64) default null,
`TranAmount` float default 0,
`PositionNbr` varchar(64) default null,
`TranReference1` varchar(64) default null,
`TranReference2` varchar(64) default null,
`TranReference3` varchar(64) default null,
`TranReference4` varchar(64) default null,
`TranDesc` varchar(64) default null,
`TranAdditionalDesc` varchar(64) default null,
`TranDate2` varchar(64) default null,
`TranDate2Invalid` varchar(64) default null,
`TranDate3` varchar(64) default null,
`TranDate3Invalid` varchar(64) default null,
`TranFTE` float default 0,
`UnitsCode` varchar(64) default null,
`UnitsDesc` varchar(64) default null,
`TranRate` float default 0,
`TranQuantity` float default 0,
`FASExplosionGenerated` varchar(64) default null,
`EncumbranceLiqCode` varchar(64) default null,
`EncumbranceLiqDesc` varchar(64) default null,
`EncFundCode` varchar(64) default null,
`EncFundDesc` varchar(64) default null,
`EncRecordType` varchar(64) default null,
`EncNbr` varchar(64) default null,
`EncBudgetNbr` varchar(64) default null,
`EncBudgetName` varchar(64) default null,
`EncAccountCode` varchar(64) default null,
`EncAccountName` varchar(64) default null,
`EncProjectCode` varchar(64) default null,
`EncTaskCode` varchar(64) default null,
`EncOptionCode` varchar(64) default null,
`CurrentAmount` float default 0,
`FuturePeriodAmount` float default 0,
`CurrentPeriodFTE` float default 0,
`FuturePeriodFTE` float default 0,
`JobClassCode` varchar(64) default null,
`JobClass` varchar(64) default null,
`ServicePeriod` varchar(64) default null,
`ServicePeriodDesc` varchar(64) default null,
`EarnTypeCode` varchar(64) default null,
`EarnType` varchar(64) default null,
`ApptNbr` varchar(64) default null,
`DistNbr` varchar(64) default null,
`VendorNbr` varchar(64) default null,
`BankCode` varchar(64) default null,
`BankDesc` varchar(64) default null,
`CashFlag` varchar(64) default null,
`CheckFlag` varchar(64) default null,
`MoneyOrderFlag` varchar(64) default null,
`DocumentSequenceInvoiceNbr` varchar(64) default null,
`PaymentSequenceNbr` varchar(64) default null,
`CheckNbr` varchar(64) default null,
`OriginatingAreaCode` varchar(64) default null,
`OriginatingAreaDesc` varchar(64) default null,
`PCATranInd` varchar(64) default null,
`PCATranDesc` varchar(64) default null,
`PCATranTypeCode` varchar(64) default null,
`PCATranTypeDesc` varchar(64) default null,
`PCADebitReferenceNbr` varchar(64) default null,
`OFISInclusionInd` varchar(64) default null,
`OFISInclusionDesc` varchar(64) default null,
`HeppsJVInd` varchar(64) default null,
`HeppsJVDesc` varchar(64) default null,
`BudgetPermTempCarryFwdCode` varchar(64) default null,
`BudgetPermTempCarryFwdDesc` varchar(64) default null,
`BeginBudgetSpreadYear` varchar(64) default null,
`BeginBudgetSpreadMonth` varchar(64) default null,
`FirstYearBudgetAmount` float default 0,
`BudgetCurrentBienniumAmount` float default 0,
`BudgetCurrentMonths` int(11) default 0,
`BudgetFacAmount` float default 0,
`BudgetFacAmountYear1` float default 0,
`BudgetFacAmountYear2` float default 0,
`BudgetFTE` float default 0,
`BudgetFTEAmountYear1` float default 0,
`BudgetFTEAmountYear2` float default 0,
`BudgetFutureBeginSpreadYM` varchar(64) default null,
`BudgetFutureBienniumAmount` float default 0,
`BudgetFutureMonths` int(11) default 0,
`BudgetGrantAmount` float default 0,
`BudgetHistoryToDateAmount` float default 0,
`FacultyActivityCount` float default 0,
`CTIRequestServiceCode` varchar(64) default null,
`CTIRequestServiceDesc` varchar(64) default null,
`CTIProgramCategoryCode` varchar(64) default null,
`CTIProgramCategoryDesc` varchar(64) default null,
`CTIBudgetNbr` varchar(64) default null,
`CTIBudgetName` varchar(64) default null,
`CTIBudgetTypeCode` varchar(64) default null,
`CTIBudgetTypeDesc` varchar(64) default null,
`CTIAccountCode` varchar(64) default null,
`CTIAccountName` varchar(64) default null,
`CTIFundCode` varchar(64) default null,
`CTIFundDesc` varchar(64) default null,
`CTIAppropriationCode` varchar(64) default null,
`CTIAppropriationDesc` varchar(64) default null,
`EmployeeIDNbr` varchar(64) default null,
`ActualHistoryToDateAmount` float default 0,
`BankDepositNbr` varchar(64) default null,
`CommodityCode` varchar(64) default null,
`ContactPhoneNbr` varchar(64) default null,
`DocumentNbr` varchar(64) default null,
`EncAdjustmentNbrPurchasing` varchar(64) default null,
`PayeeName` varchar(64) default null,
`RequisitionNbr` varchar(64) default null,
`VendorInvoiceNbr` varchar(64) default null,
`TDPrimaryKey` varchar(64) default null,
`TranOriginalDerivedCode` varchar(64) default null,
`AppTranOriginating` varchar(64) default null,
`ReferenceDocumentId` varchar(64) default null,
`GCATransTypeCode` varchar(64) default null,
`StageLoadTimestamp` varchar(64) default null,
`ODSLoadTimestamp` varchar(64) default null,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


drop table `sec_BudgetIndexCurrentBiennium`;

create table `sec_BudgetIndexCurrentBiennium` (
`id` int(11) not null auto_increment,
`timestamp` timestamp default CURRENT_TIMESTAMP(),
BienniumYear                                          varchar(64) default null,
RowStatus                                             varchar(64) default null,
BudgetNbr                                             varchar(64) default null,
BudgetNbr7                                            varchar(64) default null,
BienniumInd                                           varchar(64) default null,
EffectiveDate                                         varchar(64) default null,
EffectiveDate6                                        varchar(64) default null,
BudgetStatus                                          varchar(64) default null,
LocalFund                                             varchar(64) default null,
LocalFundDesc                                         varchar(64) default null,
LocalAppropriation                                    varchar(64) default null,
LocalAppropriationDesc                                varchar(64) default null,
RevenueClass                                          varchar(64) default null,
RevenueClassDesc                                      varchar(64) default null,
RevenueSource                                         varchar(64) default null,
RevenueSourceDesc                                     varchar(64) default null,
StateFund                                             varchar(64) default null,
StateFundDesc                                         varchar(64) default null,
StateAppropriation                                    varchar(64) default null,
StateAppropriationDesc                                varchar(64) default null,
AFund                                                 varchar(64) default null,
AFundDesc                                             varchar(64) default null,
AApprop                                               varchar(64) default null,
AAppropDesc                                           varchar(64) default null,
BFund                                                 varchar(64) default null,
BFundDesc                                             varchar(64) default null,
BApprop                                               varchar(64) default null,
BAppropDesc                                           varchar(64) default null,
CFund                                                 varchar(64) default null,
CFundDesc                                             varchar(64) default null,
CApprop                                               varchar(64) default null,
CAppropDesc                                           varchar(64) default null,
TaxStatus                                             varchar(64) default null,
ProgramCategory                                       varchar(64) default null,
ProgramCategoryDesc                                   varchar(64) default null,
FundGroup                                             varchar(64) default null,
FundGroupDesc                                         varchar(64) default null,
BudgetLevelAllotmentControl                           varchar(64) default null,
EncumbranceSalaryInd                                  varchar(64) default null,
AutoBudgetIncreaseInd                                 varchar(64) default null,
OrgCode                                               varchar(64) default null,
OrgName                                               varchar(64) default null,
OrgPresidentLevel                                     varchar(64) default null,
OrgPresidentLevelName                                 varchar(64) default null,
OrgDeanLevel                                          varchar(64) default null,
OrgDeanLevelName                                      varchar(64) default null,
OrgMajorAreaLevel                                     varchar(64) default null,
OrgMajorAreaLevelName                                 varchar(64) default null,
OrgDeptLevel                                          varchar(64) default null,
OrgDeptLevelName                                      varchar(64) default null,
OrgDivisionLevel                                      varchar(64) default null,
OrgDivisionLevelName                                  varchar(64) default null,
OrgSubDivisionLevel                                   varchar(64) default null,
OrgSubDivisionLevelName                               varchar(64) default null,
OrgParentLevel                                        varchar(64) default null,
HomeDeptLevel                                         varchar(64) default null,
HomeDeptInd                                           varchar(64) default null,
HomeDeptOrgCode                                       varchar(64) default null,
HomeDeptOrgName                                       varchar(64) default null,
OldBudgetNbr                                          varchar(64) default null,
NewBudgetNbr                                          varchar(64) default null,
BudgetName                                            varchar(64) default null,
PayrollUnitCode                                       varchar(64) default null,
PayrollUnitDesc                                       varchar(64) default null,
WorkdayUnitCode                                       varchar(64) default null,
WorkdayUnitDesc                                       varchar(64) default null,
BoxNumber                                             int(11) default 0,
BudgetType                                            varchar(64) default null,
BudgetTypeDesc                                        varchar(64) default null,
BudgetClass                                           varchar(64) default null,
BudgetClassDesc                                       varchar(64) default null,
HEGISCode                                             varchar(64) default null,
ParentGrant                                           varchar(64) default null,
ParentCapitalProject                                  varchar(64) default null,
ParentBudgetNbr                                       varchar(64) default null,
InvestmentParticipationInd                            varchar(64) default null,
CEFUnits                                              float default 0,
BienniumEndPurgeInd                                   varchar(64) default null,
FoodApprovalInd                                       varchar(64) default null,
BudgetDateAdded                                       int(11) default 0,
DeptContactAddrLine1                                  varchar(64) default null,
DeptContactAddrLine2                                  varchar(64) default null,
DeptContactAttn                                       varchar(64) default null,
DeptContactAddrCity                                   varchar(64) default null,
DeptContactPhone                                      float default 0,
DeptContactAddrState                                  varchar(64) default null,
DeptContactAddrZip                                    float default 0,
ZipCode5                                              int(11) default 0,
ZipCode4                                              Integer,
DeptContactName                                       varchar(64) default null,
DeptContactEmail                                      varchar(64) default null,
DeptContactBoxNbr                                     int(11) default 0,
DeptContactPhoneNbr                                   varchar(64) default null,
DeptDelBoxNbr                                         int(11) default 0,
ReportFunction                                        varchar(64) default null,
ReportFunctionDesc                                    varchar(64) default null,
ReportSubFunction                                     varchar(64) default null,
ReportSubFunctionDesc                                 varchar(64) default null,
CostPool                                              varchar(64) default null,
CostPoolDesc                                          varchar(64) default null,
GrantTrackingNbr                                      varchar(64) default null,
GrantContractNbr                                      varchar(64) default null,
GrantContractApplType                                 varchar(64) default null,
GrantContractAcctgCode                                varchar(64) default null,
GrantContractAdminCode                                varchar(64) default null,
GrantContractSerialNbr                                varchar(64) default null,
GrantContractGrantFiller                              varchar(64) default null,
GrantContractGrantYr                                  varchar(64) default null,
GrantContractOtherSuf                                 varchar(64) default null,
GrantContractFiller                                   varchar(64) default null,
TotalPeriodBegin                                      varchar(64) default null,
TotalPeriodEnd                                        varchar(64) default null,
FederalDocumentNbr                                    varchar(64) default null,
PrincipalInvestigator                                 varchar(64) default null,
PrincipalInvestigatorId                               varchar(64) default null,
ProgramType                                           varchar(64) default null,
ProgramTypeDesc                                       varchar(64) default null,
CostSharingInd                                        varchar(64) default null,
StandardIndirectCostRate                              float default 0,
StandardIndirectCostBase                              varchar(64) default null,
AccountingIndirectCostRate                            float default 0,
AccountingIndirectCostBase                            varchar(64) default null,
CurrentPeriodBegin                                    varchar(64) default null,
CurrentPeriodEnd                                      varchar(64) default null,
EquipmentCode                                         varchar(64) default null,
FormOfPayment                                         varchar(64) default null,
OnOffCampusInd                                        varchar(64) default null,
StandardFlowInd                                       varchar(64) default null,
GSASupplyInd                                          varchar(64) default null,
GCSponsorId                                           int(11) default 0,
CurrentPeriodBeginDate                                varchar(64) default null,
CurrentPeriodEndDate                                  varchar(64) default null,
TotalPeriodBeginDate                                  int(11) default 0,
TotalPeriodEndDate                                    int(11) default 0,
InputStatus                                           varchar(64) default null,
SalaryStatus                                          varchar(64) default null,
LastActivityDate                                      varchar(64) default null,
LastActivityDate6                                     varchar(64) default null,
CostAccountingInd                                     varchar(64) default null,
ICRateChange                                          varchar(64) default null,
StaffBenefitBudgetNbr                                 varchar(64) default null,
StaffBenefitRateCode                                  varchar(64) default null,
StaffBenefitDistCode                                  varchar(64) default null,
ControlBudgetNbr                                      varchar(64) default null,
PCAOrgCode                                            varchar(64) default null,
PCAOrgName                                            varchar(64) default null,
DefaultTask                                           varchar(64) default null,
DefaultOption                                         varchar(64) default null,
DefaultProject                                        varchar(64) default null,
LaborExpTask                                          varchar(64) default null,
LaborExpOption                                        varchar(64) default null,
LaborExpProject                                       varchar(64) default null,
RevenueTask                                           varchar(64) default null,
RevenueOption                                         varchar(64) default null,
RevenueProject                                        varchar(64) default null,
BudgetTask                                            varchar(64) default null,
BudgetOption                                          varchar(64) default null,
BudgetProject                                         varchar(64) default null,
TaskDefaultOK                                         varchar(64) default null,
OptionDefaultOK                                       varchar(64) default null,
ProjectDefaultOK                                      varchar(64) default null,
OverheadLabor                                         float default 0,
OverheadOther                                         float default 0,
OverheadDept                                          float default 0,
OverheadAdmin                                         float default 0,
CFDANbr                                               float default 0,
ExpediteApproval                                      varchar(64) default null,
ExpediteLevel                                         varchar(64) default null,
ExpediteDate                                          int(11) default 0,
LastActDate                                           int(11) default 0,
AgencyInd                                             varchar(64) default null,
BuildingCode                                          varchar(64) default null,
Misc1Rate                                             float default 0,
Misc1Base                                             varchar(64) default null,
RPCRate                                               float default 0,
RPCBase                                               varchar(64) default null,
StateAppropYear2                                      varchar(64) default null,
CloseToBudget                                         varchar(64) default null,
ProgIncomeInd                                         varchar(64) default null,
FedSalaryCap                                          float default 0,
ODSLoadTimestamp                                      varchar(64) default null,
Parameter                                             varchar(64) default null,
Value                                                 varchar(64) default null,
ParameterDesc                                         varchar(256) default null,
ODSUpdateTimestamp                                    varchar(64) default null,
PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
