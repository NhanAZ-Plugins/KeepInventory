# KeepInventory

KeepInventory preserves a player's items after death in selected Axolotl-PM worlds. It uses the server's native inventory retention flag and does not change experience handling.

## Requirements and compatibility

- Axolotl-PM API 5.0.0 or newer within the 5.x API family, as declared in [`plugin.yml`](plugin.yml). PHPStan at maximum level passes against the 5.0.0 and 5.49.1 source revisions. Intermediate releases have not been tested.
- PHP 8.1 or newer, as required by the Axolotl-PM 5.x baseline.
- No external plugin or runtime library dependency.

The exact release PHAR has been checked on Axolotl-PM 5.49.1. This confirms startup, not an in-game death and respawn. The plugin uses the world's display name for matching. Names are case-sensitive.

## Installation

Download the PHAR from [GitHub Releases](https://github.com/NhanAZ-Plugins/KeepInventory/releases), place it in your server's `plugins` directory, and restart Axolotl-PM. The plugin reads [`resources/config.yml`](resources/config.yml) when it starts.

## Configuration and behavior

```yaml
mode: blacklist
worlds: []
```

- `blacklist` preserves inventory in every world except those named in `worlds`. With the default empty list, every world is covered.
- `whitelist` preserves inventory only in worlds named in `worlds`.
- Invalid modes or world lists disable the plugin at startup with an error message. Correct the configuration and restart the server.

In worlds outside the selected set, KeepInventory leaves the death event's inventory flag alone. Other plugins can still control it. KeepInventory does not set item drops directly because Axolotl-PM uses the native retention flag to decide whether to drop and clear items.

## Build and verification

The [build workflow](.github/workflows/build.yml) runs the inventory policy tests and PHPStan at maximum level, then uses [DevTools](https://github.com/NhanAZ/DevTools) to build and inspect a downloadable PHAR. The workflow pins the tool and server source to reviewed commits. A passing workflow does not prove death and respawn behavior with a Bedrock client.

For a local build, follow the [DevTools CLI guide](https://github.com/NhanAZ/DevTools/blob/main/docs/cli.md) with this repository as the project directory. Run the policy cases with `php tests/inventory-policy.php`.

## Support and license

Report bugs in [GitHub Issues](https://github.com/NhanAZ-Plugins/KeepInventory/issues). You can also contact [NhanAZ Discord](https://discord.gg/j2X83ujT6c). KeepInventory retains its existing [GPL-3.0 license](LICENSE) and credits KhoaGamingVN in [`plugin.yml`](plugin.yml).
