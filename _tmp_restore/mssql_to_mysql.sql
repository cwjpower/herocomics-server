/**
 * Database schema required by \yii\rbac\DbManager.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @link https://www.yiiframework.com/
 * @copyright 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 * @since 2.0
 */

DROP TABLE IF EXISTS `auth_assignment`;

DROP TABLE IF EXISTS `auth_item_child`;

DROP TABLE IF EXISTS `auth_item`;

DROP TABLE IF EXISTS `auth_rule`;

create table `auth_rule`
(
    `name`  varchar(64) not null,
    `data`  blob,
    `created_at`           integer,
    `updated_at`           integer,
    primary key (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

create table `auth_item`
(
   `name`                 varchar(64) not null,
   `type`                 smallint not null,
   `description`          TEXT,
   `rule_name`            varchar(64),
   `data`                 blob,
   `created_at`           integer,
   `updated_at`           integer,
   primary key (`name`),
   foreign key (`rule_name`) references `auth_rule` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

create index `idx-auth_item-type` on `auth_item` (`type`) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

create table `auth_item_child`
(
   `parent`               varchar(64) not null,
   `child`                varchar(64) not null,
   primary key (`parent`,`child`),
   foreign key (`parent`) references `auth_item` (`name`),
   foreign key (`child`) references `auth_item` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

create table `auth_assignment`
(
   `item_name`            varchar(64) not null,
   `user_id`              varchar(64) not null,
   `created_at`           integer,
   primary key (`item_name`, `user_id`),
   foreign key (`item_name`) references `auth_item` (`name`) on delete cascade on update cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

create index `auth_assignment_user_id_idx` on `auth_assignment` (`user_id`) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TRIGGER trigger_auth_item_child
    ON `auth_item`
    INSTEAD OF DELETE, UPDATE
    AS
    DECLARE @old_name VARCHAR (64) = (SELECT name FROM deleted)
    DECLARE @new_name VARCHAR (64) = (SELECT name FROM inserted)
    BEGIN
        IF COLUMNS_UPDATED() > 0
        BEGIN
            IF @old_name <> @new_name
                BEGIN
                    ALTER TABLE auth_item_child NOCHECK CONSTRAINT FK__auth_item__child;
                    UPDATE auth_item_child SET child = @new_name WHERE child = @old_name;
                END
                UPDATE auth_item
                SET name = (SELECT name FROM inserted),
                type = (SELECT type FROM inserted),
                description = (SELECT description FROM inserted),
                rule_name = (SELECT rule_name FROM inserted),
                data = (SELECT data FROM inserted),
                created_at = (SELECT created_at FROM inserted),
                updated_at = (SELECT updated_at FROM inserted)
                WHERE name IN (SELECT name FROM deleted)
                IF @old_name <> @new_name
                    BEGIN
                        ALTER TABLE auth_item_child CHECK CONSTRAINT FK__auth_item__child;
                    END
                END
                ELSE
                    BEGIN
                        DELETE FROM `auth_item_child` WHERE parent IN (SELECT name FROM deleted) OR child IN (SELECT name FROM deleted) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                        DELETE FROM `auth_item` WHERE name IN (SELECT name FROM deleted) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                    END
        END;

