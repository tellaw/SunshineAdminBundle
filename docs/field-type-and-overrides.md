# Field type and overrides

### Configuration of data types

There is no configuration required for type of your entities attributes. Anyway, the application is going to try to find type description in your **assert** or **doctrine** annotations.

* [Assert documentation](https://symfony.com/doc/current/validation.html)
* [Doctrine annotations](https://symfony.com/doc/current/doctrine.html)

### Inheritance of types

The application is looking for the following order to extract field type informations.

| Priority | Description |
| --- | --- | --- | --- | --- |
| 1 \(most important\) | Yaml configuration for **Form** Section or **List** Section |
| 2 | Yaml configuration for **Attributes** section |
| 3 | Assert annotations |
| 4 \(last\) | Doctrine annotations |

```yaml
tellaw_sunshine_admin:
  entities:
      partner :
           configuration:
               id: id
               class: AppBundle\Entity\Partner

           attributes:
               id:
                  label: Id
                  type : integer

               name:
                  label: Nom
                  type : string
                  sortable : true

           form:
              fields:
                    name: ~

           list:
              fields:
                  id :
                      label: Identifiant
                  name :
                      label: Nom de société

              filters:
                  name :
                      label: filter-label

              search:
                  name :
                      label: search-label
```

Using this configuration, Sunshine will decide that the field 'name' is a String by reading the 'attributes' section of YAML

If a value was configured in the sections 'form' or 'list', sunshine would use them istead of the attributes value in the correct context.

{% hint style="info" %}
If you used a custom formType, it is **your responsability** to handle the configuration override.
{% endhint %}

### Doctrine Annotation

```php
    /**
     * @var \DateTime $validityStartDate
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $validityStartDate;
```

Using this doctrine annotation, Sunshine will consider this field as a datetime field.

### Assert Annotation

```php
    /**
     * @var string $name
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     *
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotNull()
     */
    private $name;
```

Using this configuration, Sunshine will use the Asset\Type configuration instead of Doctrine due to priority.

