# Release checklist

Use this checklist for every tagged release of `cinghie/yii2-adminlte3`. A major/minor release should complete every applicable item; patch releases may mark non-applicable items explicitly rather than silently skipping them.

## 1. Scope and compatibility

- [ ] Confirm the intended version follows `docs/VERSIONING.md`.
- [ ] Review public classes, widget properties/defaults, compatibility aliases, AssetBundles, runtime minimums, and implicit asset behaviour for accidental breaking changes.
- [ ] Confirm every deprecation remains available for the current major release and has a documented replacement.
- [ ] Record any required migration or security-sensitive behaviour change in `CHANGELOG.md`.

## 2. Documentation and package metadata

- [ ] Confirm `README.md` installation instructions target the release line, not a development branch.
- [ ] Synchronize `README.md`, `CHANGELOG.md`, and `UPDATE.md` with the code being tagged.
- [ ] Verify `composer.json` name, description, license, runtime constraints, autoload, and development tooling.
- [ ] Verify `LICENSE` and Composer license metadata agree.
- [ ] Ensure public documentation contains no private project names, hosts, credentials, personal paths, or non-public operational details.

## 3. Automated validation

- [ ] Run `composer validate --strict`.
- [ ] Perform a clean dependency installation.
- [ ] Run PHP syntax lint.
- [ ] Run PHPUnit on every supported PHP minor in the CI matrix.
- [ ] Run `composer analyse` (PHPStan).
- [ ] Run `composer cs` (PHP_CodeSniffer / PSR-12).
- [ ] Confirm required GitHub Actions workflows are green on the exact release commit.
- [ ] Confirm the PHP 8.1 `prefer-lowest` job resolves, installs from source, verifies the critical autoload surface, lints, and passes the full PHPUnit suite.

## 4. Assets and browser-facing behaviour

- [ ] Verify core and compatibility aggregate dependency graphs.
- [ ] Verify source/minified asset parity and declared vendor-file existence.
- [ ] Confirm optional plugin bundles remain isolated from unrelated pages.
- [ ] Confirm package-owned widget AssetBundles publish from package-local paths and only publish their intended files.
- [ ] Confirm Bootstrap/jQuery/plugin ordering has not changed unexpectedly.
- [ ] Smoke-render representative public widgets, including complex/navigation/security-sensitive widgets and newly added reusable input widgets.
- [ ] For browser-facing changes, verify AdminLTE visual fidelity, responsive behaviour, accessibility attributes, CSP expectations, and relevant browser/plugin integration.
- [ ] For DatePicker/DateTimePicker changes, exercise at least one real Bootstrap 4/AdminLTE3 Tempus Dominus open/select/clear interaction before release.

## 5. Release candidate smoke install

Before tagging, test the exact release commit from a clean minimal Yii2 application or equivalent clean Composer project.

- [ ] Require the package from the exact commit/repository state that will be tagged.
- [ ] Confirm Composer resolves all required runtime dependencies without relying on the package development checkout.
- [ ] Register `AdminLTECoreAsset` and the compatibility aggregate at least once.
- [ ] Smoke-render representative widgets and any newly introduced public widget/AssetBundle.
- [ ] Confirm package assets publish successfully in a clean runtime/assets directory.

## 6. Tag and publish

- [ ] Re-check that the release commit is the intended `main` HEAD and CI is green.
- [ ] Create the SemVer tag without changing source metadata solely to embed the version.
- [ ] Publish the GitHub Release with concise highlights, compatibility requirements, security notes, deprecations, and migration notes where applicable.
- [ ] Verify Packagist recognizes the new tag and the stable Composer constraint resolves it.
- [ ] Perform a final clean install using the tagged constraint (for 1.0.0: `composer require cinghie/yii2-adminlte3:^1.0`).
- [ ] Verify release badges/links and public installation documentation resolve to the published stable release.

## 7. Post-release

- [ ] Check the first public CI/consumer feedback for packaging, asset, or dependency regressions.
- [ ] Add newly discovered follow-up work to `UPDATE.md` rather than rewriting released history.
