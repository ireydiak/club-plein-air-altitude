-- ---------------------------------------------------------------------------------------------------------------------
-- Administration des membres: Member, Permanent, Admin, MemberFacebook, MemberUniversity, MemberEmail
-- ---------------------------------------------------------------------------------------------------------------------

--
-- Table "Member"
-- Représente un membre du club.
-- Contient un identifiant unique, un prénom, un nom et une date d'ajout.
-- Attention: doit obligatoirement avoir un contact (email, cip, facebook)
--
CREATE TABLE Member (
    member_id    BIGINT AUTO_INCREMENT NOT NULL,
    first_name   VARCHAR(255)          NOT NULL,
    last_name    VARCHAR(255)          NOT NULL,
    password     VARCHAR(255)          NOT NULL,
    date_created TIMESTAMP             NOT NULL,
    CONSTRAINT member_pk PRIMARY KEY (member_id)
)
    ENGINE = InnoDB,
    DEFAULT CHARSET = utf8
;

--
-- Table "MemberUniversity"
-- Représente un moyen de contact pour un membre à l'aide de son cip
--
CREATE TABLE MemberUniversity (
    member_id BIGINT  NOT NULL,
    cip       CHAR(8) NOT NULL,
    CONSTRAINT member_university_pk PRIMARY KEY (member_id),
    CONSTRAINT member_university_fk FOREIGN KEY (member_id) REFERENCES Member (member_id)
        ON DELETE CASCADE
)
    ENGINE = InnoDB,
    DEFAULT CHARSET = utf8
;

--
-- Table "MemberEmail"
-- Représente un membre avec une adresse email
--
CREATE TABLE MemberEmail (
    member_id BIGINT       NOT NULL,
    email     VARCHAR(255) NOT NULL,
    CONSTRAINT member_email_pk PRIMARY KEY (member_id),
    CONSTRAINT member_emaik_fk FOREIGN KEY (member_id) REFERENCES Member (member_id)
        ON DELETE CASCADE,
    CONSTRAINT member_email_unique UNIQUE (email)
)
    ENGINE = InnoDB,
    DEFAULT CHARSET = utf8
;

--
-- Table "MemberFacebook"
-- Représente un membre avec un moyen de contact Facebook
--
CREATE TABLE MemberFacebook (
    member_id     BIGINT       NOT NULL,
    facebook_link VARCHAR(255) NOT NULL,
    CONSTRAINT member_pk PRIMARY KEY (member_id),
    CONSTRAINT member_facebook_fk FOREIGN KEY (member_id) REFERENCES Member (member_id)
        ON DELETE CASCADE,
    CONSTRAINT member_facebook_unique UNIQUE (facebook_link)
)
    ENGINE = InnoDB,
    DEFAULT CHARSET = utf8
;

--
-- Table "Permanent"
-- Représente un type spécial de membre pouvant effectuer des locations
--
CREATE TABLE Permanent (
    member_id BIGINT       NOT NULL,
    CONSTRAINT permanent_pk PRIMARY KEY (member_id),
    CONSTRAINT permanent_fk FOREIGN KEY (member_id) REFERENCES Member (member_id)
        ON DELETE CASCADE
)
    ENGINE = InnoDB,
    DEFAULT CHARSET = utf8
;

--
-- Table "Admin"
-- Représente un type spécial de membre participant à l'administration du club
-- Attention: un administrateur est aussi un permanent
--
CREATE TABLE Admin (
    member_id BIGINT NOT NULL,
    CONSTRAINT admin_pk PRIMARY KEY (member_id),
    CONSTRAINT admin_fk FOREIGN KEY (member_id) REFERENCES Permanent (member_id)
        ON DELETE CASCADE
)
    ENGINE = InnoDB,
    DEFAULT CHARSET = utf8
;
