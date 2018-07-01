
# Versioning


## Generate Version file from the tag list


```
php -r 'exec("git tag", $tags); sort($tags, SORT_STRING); $last = array_pop($tags); file_put_contents(".version", $last);'
```

Or

```
git describe --abbrev=0 --tags > .version
```
