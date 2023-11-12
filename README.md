# Corals Trouble Ticket

Laraship Support Management module can be used to manage and track support requests from both registered users and normal website visitors.

it’s shipped with the Laraship Elite platform and it can be purchased separately from our store.

Once you install the module the following items need to be configured.

1. Team Setup

2. Ticket Types

3. Linking Current Models (Optional)

<p>&nbsp;</p>
<p><img src="https://www.laraship.com/wp-content/uploads/2020/08/laravel-trouble-ticket-module-1024x553.png" alt=""></p>

### Articles
- [Team Setup](#team-setup)

- [Tickets (Issue) Types](#tickets-issue-types)

- [Linking with existing Models](#linking-with-existing-models)

<p>&nbsp;</p>

## Installation

You can install the package via composer:

```bash
composer require corals/trouble-ticket
```

## Testing

```bash
vendor/bin/phpunit vendor/corals/trouble-ticket/tests 
```


## Team Setup
Teams are basically a set of users specialized in following up trouble tickets, they can be assigned to all trouble tickets or tickets with specific categories, for example, Billing, Technical.

<p><img src="https://www.laraship.com/wp-content/uploads/2020/08/laravel-support-team-management-1-1024x308.png" alt=""></p>
<p>&nbsp;</p>

- You can assign a group email in case you have it set up on your organization, for example, billing@example.com. also you can define a slack webhook URL to post to this channel trouble ticket updates.

### Ticket Assignment:
Trouble Tickets can be manually assigned or automatically assigned, admin can assign tickets or tickets can be reassigned to another team member during the ticketing cycle.

The automatic assignment is based on the minimum load score, basically, who have the least tickets opened.
<p>&nbsp;</p>

## Tickets (Issue) Types
Issue types help the support team to decide whom this ticket should be assigned to, and they route the ticket to the related team.

for example, the Billing team will handle invoice issues, payment problems, refunds and so on

The technical team will handle support issues like login errors, errors on the application…

 <p>&nbsp;</p>

Also, you can define multiple possible solutions to issues, for example, if someone cannot log in to his account you can suggest him to reset his password or to check junk folder, …

<p><img src="https://www.laraship.com/wp-content/uploads/2020/08/laravel-support-issue-type-management-1024x312.png" alt=""></p>
<p>&nbsp;</p>

## Linking with existing Models
Tickets can be associated with models within Laraship, for example, a user wants to raise a support ticket about a specific subscription, or he has an issue with a specific order, in this case, you can have a dropdown on ticket creation to select the order or the subscription he is referring to

Models are hooked up with the trouble ticket using the config inside the Model, for example, if you check the Subscription module:

under Corals/modules/TroubleTicket/config/trouble_ticket.php config file, you can find the available models

```php
[
    'path' => 'subscriptions.models.subscription',
    'public' => false,
    'scopes' => [
        \Corals\Modules\TroubleTicket\Scopes\SubscriptionsScope::class,
    ]
],
```
<p>&nbsp;</p>

you can see the model related, is it enabled on public, and if there are any scopes, for example showing only my subscriptions…

and under the order config you

```php
'ajaxSelectOptions' => [
                'label' => 'Subscription',
                'model_class' => \Corals\Modules\Subscriptions\Models\Subscription::class,
                'columns' => ['subscription_reference'],
            ]
```

<p>&nbsp;</p>
which defined which data to be shown inside the dropdown.

trouble_ticket.php can be overridden by copying it under config folder.

## Hire Us
Looking for a professional team to build your success and start driving your business forward.
Laraship team ready to start with you [Hire Us](https://www.laraship.com/contact)
