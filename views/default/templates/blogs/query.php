if(unix_timestamp(now())<(unix_timestamp(b.created)+60),concat(unix_timestamp(now())-unix_timestamp(b.created),' seconds ago'),
                if(unix_timestamp(now())<(unix_timestamp(b.created)+120),'over a minute ago',
                        if(unix_timestamp(now())<(unix_timestamp(b.created)+(60*60)),concat(round((unix_timestamp(now())-unix_timestamp(b.created))/60),' minutes ago'),
                                if(unix_timestamp(now())<(unix_timestamp(b.created)+60*120),'over an hour ago',
                                        if(unix_timestamp(now())<(unix_timestamp(b.created)+(60*50*24)),concat(round(((unix_timestamp(now())-unix_timestamp(b.created))/60/60)),' hours ago'),
                                                concat('at ',date_format(b.created,'%l:%i'),lower(date_format(b.created,'%p')),date_format(b.created,' %o%n %a, %D %b, %Y')))))))as createdFriendly_comment