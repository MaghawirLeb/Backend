CREATE TABLE IF NOT EXISTS `AccountPhoto` (
  `AccountPhotoId` INT PRIMARY KEY AUTO_INCREMENT,
  `AccountId`      INT          NOT NULL,
  `FileName`       VARCHAR(100) NOT NULL
)