# Versioning and deprecation policy

`cinghie/yii2-adminlte3` follows Semantic Versioning for its public package API starting with version 1.0.0.

## Public compatibility contract

The compatibility contract covers public PHP classes, widget properties and documented defaults, AssetBundle classes and dependency behaviour, supported runtime baselines, package-owned rendering/security defaults, and documented configuration examples intended as public API.

Internal classes or helpers marked `@internal` are not part of the stable public API and may change in backward-compatible releases when required to maintain or improve the public contract.

## Major releases

A major release is required for intentional backward-incompatible changes, including:

- removing a public class, widget property, method, or documented compatibility alias;
- removing an implicitly registered asset or changing an AssetBundle dependency in a way that requires application changes;
- increasing the minimum supported PHP, Yii, Bootstrap, or other required runtime baseline when existing supported applications would no longer install;
- changing a documented default in a way that materially changes rendering, security, navigation, encoding, or browser behaviour for existing applications;
- removing deprecated compatibility surfaces such as `Box` or historical widget property aliases.

Security fixes may occasionally require a safer default even when the observable behaviour changes. Such changes must be documented prominently in the changelog with migration guidance. When a safe compatibility path exists, it should be preferred.

## Minor releases

A minor release may add backward-compatible functionality, including:

- new widgets, options, AssetBundles, translations, or documented extension points;
- new optional plugin integrations that are not added implicitly to existing core/aggregate bundles;
- new canonical property names when existing names remain available as deprecated aliases;
- performance, accessibility, CSP, or security improvements that preserve the documented public contract.

## Patch releases

Patch releases are for backward-compatible bug, security, documentation, test, packaging, and performance fixes that do not intentionally expand or break the public API.

## Deprecation policy

Public APIs are deprecated before removal whenever a practical compatibility path exists.

- A deprecated public class/property remains callable throughout the current major release.
- Deprecations are documented in PHPDoc and the changelog and should identify the preferred replacement.
- Deprecated APIs are covered by regression tests while they remain supported.
- Removal is reserved for the next major release; a minor or patch release must not remove an existing deprecated public API.
- If a replacement alias and its legacy name are both configured, the documented canonical property takes precedence.

For the 1.x line this means the deprecated `Box` compatibility facade and historical widget property aliases remain supported for the complete 1.x major series. Their removal may only be considered for 2.0.0 or later.

## Dependency and asset changes

Dependency updates must preserve the declared runtime constraints of the current major line unless a major release explicitly raises them. Optional plugin assets should remain page-scoped and must not be added to the core or compatibility aggregate implicitly without considering the change a public compatibility decision.

The normal CI runtime matrix is the supported compatibility signal. The separate `prefer-lowest` job remains observational until the legacy minimum dependency ecosystem can be installed and executed reliably; successful dependency resolution alone is not a runtime-support guarantee.
