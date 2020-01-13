DROP VIEW IF EXISTS member_view;
CREATE VIEW member_view AS
SELECT DISTINCT member_id, first_name, last_name, password, created_at, updated_at, email, facebook_link, cip,
                EXISTS(SELECT member_id FROM admin NATURAL JOIN member) AS is_admin,
                EXISTS(SELECT member_id FROM permanent NATURAL JOIN member) AS is_permanent
FROM member LEFT JOIN member_email      USING(member_id)
            LEFT JOIN member_facebook   USING(member_id)
            LEFT JOIN member_university USING(member_id)
;
