# digitalia-module-digitalia_muni_universal_form

Allows using single content type and have separate forms depending on group.

Form alteration can be enabled for each content type. Allowed custom fields (with machine name field_\*) can be selected for each group.


## Example
Two groups are present:
- Group01
- Group02

Four users are present:
- User01 (member of Group01)
- User02 (member of Group02)
- User03 (member of Group01 and Group02)
- User04 (not member of any group)

Content type 'Article' has two fields ('Image' and 'Tags'). Three nodes of type 'Article' are present:
- Article01 (node of Group01)
- Article02 (node of Group02)
- Article03 (node of Group01 and Group02)
- Article04 (not in any group)

In module settings, content type 'Article' is enabled and following fields for groups are selected:
- Group01 - 'Image'
- Group02 - 'Tags'

Now let's see the forms from points of view of different users:
|              | Article01 | Article02 | Article03 | Article04 |
|--------------|-----------|-----------|-----------|-----------|
| User01       | Image     |           | Image     |           |
|              |           |           |           |           |
|--------------|-----------|-----------|-----------|-----------|
| User02       |           | Tags      | Tags      |           |
|              |           |           |           |           |
|--------------|-----------|-----------|-----------|-----------|
| User03       | Image     | Tags      | Image     |           |
|              |           |           | Tags      |           |
|--------------|-----------|-----------|-----------|-----------|
| User04       |           |           |           |           |
|              |           |           |           |           |
|--------------|-----------|-----------|-----------|-----------|
