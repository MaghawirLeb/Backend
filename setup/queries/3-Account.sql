CREATE TABLE IF NOT EXISTS `Account` (
  `AccountId`      INT PRIMARY KEY AUTO_INCREMENT,
  `Name`           VARCHAR(100)
                   CHARACTER SET UTF8                  NOT NULL,
  `BusinessTypeId` INT                                 NOT NULL,
  `Description`    VARCHAR(1000)
                   CHARACTER SET UTF8,
  `Logo`           VARCHAR(200),
  `Address`        VARCHAR(200)
                   CHARACTER SET UTF8,
  `Telephone`      VARCHAR(32)
                   CHARACTER SET UTF8,
  `MobilePhone`    VARCHAR(32)
                   CHARACTER SET UTF8,
  `RegionId`       INT                                 NOT NULL,
  `Longitude`      VARCHAR(16),
  `Latitude`       VARCHAR(16),
  `CreatedOn`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `ModifiedOn`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

  INDEX `region_ind` (`RegionId`),
  INDEX `businesstype_ind` (`BusinessTypeId`),

  FOREIGN KEY (`BusinessTypeId`)
  REFERENCES BusinessType (`BusinessTypeId`)
    ON DELETE RESTRICT,

  FOREIGN KEY (`RegionId`)
  REFERENCES Region (`RegionId`)
    ON DELETE RESTRICT
)