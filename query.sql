SELECT 
	t.id
FROM (
	SELECT 
		t.*,
		coalesce(
			sum(t.amount) over (
				order by t.date rows between unbounded preceding and current row
			), 0) as total
	FROM 
		tm.transactions t 
	order by t.date asc
) t
WHERE
	t.total < 0
LIMIT 1
