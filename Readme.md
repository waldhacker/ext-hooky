# Hooky - Webhooks for TYPO3

Send webhooks for any event in TYPO3 - makes it possible to use TYPO3 with
third party services such as automate.io, Zapier, etc.

## Installation

This extension is provided as composer package only as it makes use of the
enqueue package for message queue handling which is installed as composer
dependency.

`composer req waldhacker/hooky`

## Configuration

The extension comes with a custom backend module that allows the creation of new
web hooks. By default, it knows about two types of events - record updates or creation -
and will send the raw data as message body. In most cases you will want to use
custom events - see below for instructions.

Enter the target URL and (optional) secret for the webhook and choose the events
to listen to.

Use the following two commands (ideally run regularly via scheduler, cron or system.d)
to trigger processing of the message queue and message sending:

- `./bin/typo3 hooky:hooks:queue` - will check whether there are hooks configured for events that occurred and queue a message for each configured hook.
- `./bin/typo3 hooky:hooks:send` - will process the queue and send the hook requests

### Hook Signing

The hook request is signed with the configured secret which is sent base64 encoded
in the header `X-TYPO3-HookSignature`. To verify the content on the receiving side,
create a hash of the arriving content and compare it to the header.

Example:
`hash_hmac('sha256', $content, $secret)`

## Using custom events

An event that triggers a hook needs to fulfil two requirements:

- it needs to implement the interface `JsonSerializable`
- it needs to be tagged as hookable event in the DI configuration

The data that is returned by jsonSerialize will be the data enclosed in the message
body of the web hook.

The DI configuration looks like this - as an example taken from this extension directly:

```yaml
  Waldhacker\Hooky\Events\RecordUpdatedEvent:
    tags:
      - name: hooky.hookable
        label: 'LLL:EXT:hooky/Resources/Private/Language/hooky.xlf:event.recordUpdated.label'
        description: 'LLL:EXT:hooky/Resources/Private/Language/hooky.xlf:event.recordUpdated.description'
```

The label and description are displayed in the TYPO3 backend when selecting the event in
the webhook configuration.

In many cases, you will probably implement an event listener to a TYPO3 core event and
let that event listener dispatch your custom event that in turn is hookable and json serializable. For example: You could add an event listener listening to the file uploaded event and automatically notify an external image service via webhook.
