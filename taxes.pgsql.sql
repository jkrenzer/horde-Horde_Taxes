-- $Horde: incubator/Horde_Taxes/taxes.pgsql.sql,v 1.5 2008/04/04 13:41:07 jan Exp $
--
CREATE TABLE horde_taxes (
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    value FLOAT NOT NULL,
    sort INT,
    created INT,
    updated INT,

    PRIMARY KEY (id)
);
